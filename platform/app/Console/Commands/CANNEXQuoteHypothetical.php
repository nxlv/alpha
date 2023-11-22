<?php

namespace App\Console\Commands;

use App\Http\Helpers\CANNEXHelper;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        if ( Storage::exists( 'banner-cannex.asc' ) ) {
            $this->line( '<fg=blue>' . Storage::get( 'banner-cannex.asc' ) . '</>' );
        }

        $this->line( sprintf( '  <bg=blue;fg=black> CANNEX Hypothetical Quote Generator </> v%s', $this->version ) . PHP_EOL );

        $transaction_id = uuid_create();

        $analysis_id = $this->argument( 'analysis_id' );
        $premium = preg_replace( '/[^0-9.]/', '', $this->argument( 'premium' ) );
        $deferral = $this->argument( 'deferral' );
        $age = $this->argument( 'age' );
        $gender = $this->argument( 'gender' );
        $type = $this->argument( 'type' );
        $state = $this->argument( 'state' );
        $start_date = $this->argument( 'index_start_date' );
        $index_start_date = time();

        if ( strtotime( $start_date ) ) {
            $index_start_date = strtotime( $start_date );
        }

        if ( ( !empty( $analysis_id ) ) && ( !empty( $premium ) ) && ( !empty( $age ) ) && ( !empty( $type ) ) ) {
            $this->line( sprintf( '  <bg=blue;fg=black> PARAMETERS </> ID: %s | Inv: %f | Def: %d | Age: %d | Gender: %s | Type: %s | State: %s | Index Start Date: %s', $analysis_id, $premium, $deferral, $age, $gender, $type, $state, $start_date ) . PHP_EOL );

            $request = [
                'contract_cd'                 => $type,
                'premium'                     => $premium,
                'purchase_date'               => gmdate( 'Y-m-d\TH:i:s.v\Z' ),
                'gender_cd_primary'           => $gender,
                'gender_cd_joint'             => null,
                'purchase_age_primary'        => $age,
                'purchase_age_joint'          => null,
                'income_start_age_primary'    => ( $age + $deferral ),
                'income_start_age_joint'      => null,
                'anty_ds_version_id'          => CANNEXHelper::ANTY_ANLY_VERSION_ID,
                'index_time_range'            => [
                    'start_month' => gmdate( 'n', $index_start_date ),
                    'start_year' => ( gmdate( 'Y', $index_start_date ) - ( 2 + $deferral ) ),
                    'end_month' => 12,
                    'end_year' => ( gmdate( 'Y', $index_start_date ) - 2 )
                ],
                'analysis_cd'                 => 'I',
                'analysis_data_id'            => $analysis_id,
                'analysis_time_horizon_years' => ( $deferral + 1 ),
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
            }

            $this->line( PHP_EOL . '  <bg=cyan;fg=black> DONE! </>' . PHP_EOL );

            return Command::SUCCESS;
        } else {
            $this->line( sprintf( '  <bg=red;fg=white> ERROR </> Some required parameters missing.' ) . PHP_EOL );

            return Command::FAILURE;
        }
    }
}
