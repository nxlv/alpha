<?php

namespace App\Console\Commands;

use App\Http\Helpers\CANNEXHelper;

use App\Http\Helpers\ProductHelper;
use App\Models\ProductsInstancesStrategy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

use App\Models\Product;
use App\Models\Index;

class CANNEXQuoteHypothetical extends Command {
    protected $version = '1.0';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cannex:quote-hypothetical {analysis_id} {premium} {deferral} {age} {gender} {type} {state?} {index_start_date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches a real-time quote for guaranteed income based on the parameters specified.';

    /**
     * Local cached storage of index data
     *
     * @var array
     */
    public static $indexes = [];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        if ( Storage::exists( 'banner-cannex.asc' ) ) {
            $this->line( '<fg=blue>' . Storage::get( 'banner-cannex.asc' ) . '</>' );
        }

        $this->line( sprintf( '  <bg=blue;fg=black> CANNEX Hypothetical Quote Generator </> v%s', $this->version ) . PHP_EOL );

        $analysis_id = $this->argument( 'analysis_id' );
        $premium = preg_replace( '/[^0-9.]/', '', $this->argument( 'premium' ) );
        $deferral = $this->argument( 'deferral' );
        $age = $this->argument( 'age' );
        $gender = $this->argument( 'gender' );
        $type = $this->argument( 'type' );
        $state = $this->argument( 'state' );
        $start_date = $this->argument( 'index_start_date' );

        if ( !empty( $start_date ) ) {
            $analysis_purchase_date = date( 'Y-m-d', strtotime( $start_date ) );
        } else {
            $analysis_purchase_date = date( 'Y-m-d', time() );
        }

        if ( ( !empty( $analysis_id ) ) && ( !empty( $premium ) ) && ( !empty( $age ) ) && ( !empty( $type ) ) ) {
            $this->line( sprintf( '  <bg=blue;fg=black> PARAMETERS </> ID: %s | Inv: %f | Def: %d | Age: %d | Gender: %s | Type: %s | State: %s | Index Start Date: %s', $analysis_id, $premium, $deferral, $age, $gender, $type, $state, $analysis_purchase_date ) . PHP_EOL );

            $analysis_record = Product::where( 'analysis_data_id', $analysis_id )->with( 'instances', 'instances.strategies' )->get();

            if ( $analysis_record->count() ) {
                // override index date if necessary
                $record = $analysis_record->first();

                $index_date_oldest = null;
                $index_date_newest = null;
                $index_id = ProductsInstancesStrategy::where( 'instance_id', $record->strategy_details_instance_id )->get()->pluck( 'index_id' );

                if ( $index_id->count() ) {
                    $index_id = $index_id->first();

                    // cache index data
                    if ( empty( self::$indexes ) ) {
                        if ( !Cache::has( 'alpha__fia-indexes' ) ) {
                            $query = \App\Models\Index::all();

                            Cache::add( 'alpha__fia-indexes', $query, ( 60 * 60 ) );    // 60 minutes

                            self::$indexes = $query;
                        } else {
                            self::$indexes = Cache::get( 'alpha__fia-indexes' );
                        }
                    }

                    foreach ( self::$indexes as $index ) {
                        if ( $index->index_id === $index_id ) {
                            $index_date_oldest = $index->oldest_date;
                            $index_date_newest = $index->most_recent_date;
                            break;
                        }
                    }
                } else {
                    $index_id = null;
                }

                if ( ( !empty( $index_id ) ) && ( !empty( $index_date_oldest ) ) && ( ( !empty( $index_date_newest ) ) ) ) {
                    $index_data_limits = ProductHelper::validate_index_dates( $analysis_id, $analysis_purchase_date, $deferral );

                    $request = [
                        'contract_cd'                 => $type,
                        'premium'                     => $premium,
                        'purchase_date'               => gmdate( 'Y-m-d\TH:i:s.v\Z' ),
                        'gender_cd_primary'           => $gender,
                        'gender_cd_joint'             => null,
                        'purchase_age_primary'        => $age,
                        'purchase_age_joint'          => null,
                        'income_start_age_primary'    => intval( $age ) + intval( $index_data_limits[ 'deferral' ] ),
                        'income_start_age_joint'      => null,
                        'index_time_range'            => array(
                            'start_month' => $index_data_limits[ 'index_date_end' ]->format( 'n' ),
                            'start_year' => $index_data_limits[ 'index_date_end' ]->format( 'Y' ),
                            'end_month' => $index_data_limits[ 'index_date_start' ]->format( 'n' ),
                            'end_year' => $index_data_limits[ 'index_date_start' ]->format( 'Y' )
                        ),
                        'anty_ds_version_id'          => Config::get( 'cannex.version' ),
                        'analysis_cd'                 => 'B',
                        'analysis_data_id'            => $analysis_id,
                        'analysis_time_horizon_years' => $index_data_limits[ 'deferral' ] + 1,
                        'is_test'                     => 'N'
                    ];

                    $response = CANNEXHelper::analyze_fixed( $request );

                    if ( $response ) {
                        foreach ( $response as $row ) {
                            if ( isset( $row->analysis_error ) ) {
                                $this->line( PHP_EOL . '  <bg=red;fg=white> ERROR </> ' . $row->analysis_error->error_message );
                            } else if ( isset( $row->analysis_data ) ) {
                                foreach ( $row->analysis_data as $prediction ) {
                                    if ( $prediction->income > 0 ) {
                                        $this->line( '  <bg=green;fg=black> INCOME </> $' . number_format( $prediction->income, 2, '.', ',' ) . '/year starting at age ' . $prediction->primary_age );
                                        break;
                                    }
                                }
                            }
                        }
                    } else {
                        $this->line( PHP_EOL . '  <bg=red;fg=white> ERROR </> No response from CANNEX server.' );
                    }
                } else {
                    $this->line( PHP_EOL . '  <bg=red;fg=white> ERROR </> Index dates could not be determined.' );
                }
            }

            $this->line( PHP_EOL . '  <bg=cyan;fg=black> DONE! </>' . PHP_EOL );

            return Command::SUCCESS;
        } else {
            $this->line( sprintf( '  <bg=red;fg=white> ERROR </> Some required parameters missing.' ) . PHP_EOL );

            return Command::FAILURE;
        }
    }
}
