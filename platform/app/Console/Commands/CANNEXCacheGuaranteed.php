<?php

namespace App\Console\Commands;

use App\Http\Helpers\ProductHelper;
use App\Http\Helpers\CANNEXHelper;
use App\Models\AnalysisGuaranteedCache;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CANNEXCacheGuaranteed extends Command {
    const DEFAULT_PREMIUM_BREAKPOINTS = [ '100', '100000' ];
    const DEFAULT_DEFERRAL_INTERVALS = [ 5, 10, 20 ];
    const DEFAULT_OWNER_STATE = 'FL';
    const DEFAULT_MARITAL_STATUSES = [ 'S', 'J' ];
    const DEFAULT_PURCHASE_AGE = 50;
    const ANALYSIS_MAX_CHUNK_SIZE = 25;

    protected $version = '1.0';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cannex:cache-guaranteed {--marital=S} {--premium=100.00} {--deferral=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Caches guarnateed income quotes for preset investment amounts across all products.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        if ( Storage::exists( 'banner-cannex.asc' ) ) {
            $this->line( '<fg=blue>' . Storage::get( 'banner-cannex.asc' ) . '</>' );
        }

        $marital_status = $this->option( 'marital' );
        $premium = $this->option( 'premium' );
        $deferral = $this->option( 'deferral' );

        if ( in_array( $marital_status, self::DEFAULT_MARITAL_STATUSES ) === false ) {
            $this->line( '  <bg=red;fg=white> ERROR </> Invalid marital status provided.' . PHP_EOL );
            exit;
        }

        if ( !is_numeric( $premium ) ) {
            $this->line( '  <bg=red;fg=white> ERROR </> Invalid premium amount provided.' . PHP_EOL );
            exit;
        } else {
            $premium = number_format( floatval( $premium ), 2, '.', '' );
        }

        if ( !is_numeric( $deferral ) ) {
            $this->line( '  <bg=red;fg=white> ERROR </> Invalid deferral period provided.' . PHP_EOL );
            exit;
        }

        $this->line( sprintf( '  <bg=blue;fg=white> CANNEX Cache Builder </> v%s', $this->version ) . PHP_EOL );
        $this->line( PHP_EOL . '  <fg=white;bg=blue> NOTICE </> Obtaining default product list...' . PHP_EOL );
        $this->line( sprintf( '  <fg=white;bg=green> CONFIG </> Marital status: %s', $marital_status ) );
        $this->line( sprintf( '  <fg=white;bg=green> CONFIG </> Premium: %f', $premium ) );
        $this->line( sprintf( '  <fg=white;bg=green> CONFIG </> Deferral: %d', $deferral ) );

        $products = ProductHelper::identify_products();

        if ( ( !empty( $products ) ) && ( count( $products ) ) ) {
            $stack = [];

            $this->line( sprintf( '  <fg=white;bg=blue> NOTICE </> %d top-level products queued.', count( $products ) ) . PHP_EOL );

            foreach ( $products as $product ) {
                $this->line( PHP_EOL . sprintf( 'Adding <bg=yellow;fg=black> %s </>', $product[ 'product' ][ 'name' ] ) );

                if ( count( $product[ 'targets' ] ) ) {
                    foreach ( $product[ 'targets' ] as $target ) {
                        $this->line( sprintf( '* ADID: <bg=gray;fg=white> %s </> 🎯 Instance Premium Range: <bg=green;fg=black> %d ➡️ %d </> 🎯 Premium: <bg=cyan;fg=black> %s </> 🎯 Deferral Period: <bg=cyan;fg=black> %d </>', $target[ 'product_analysis_data_id' ], $target[ 'premium_range_min' ], $target[ 'premium_range_max' ], $premium, $deferral ) );
                        $this->line( sprintf( '  🎯 Valid States: <bg=white;fg=black> %s </>', ( ( !empty( $target[ 'rules' ][ 'valid_states' ] ) ) ? $target[ 'rules' ][ 'valid_states' ] : 'Any/all' ) ) );

                        if ( !empty( $target[ 'rules' ][ 'valid_states' ] ) ) {
                            $valid_states = explode( ',', trim( $target[ 'rules' ][ 'valid_states' ] ) );

                            if ( array_search( self::DEFAULT_OWNER_STATE, $valid_states ) !== false ) {
                                $owner_state = self::DEFAULT_OWNER_STATE;
                            } else {
                                $owner_state = $valid_states[ 0 ];
                            }
                        } else {
                            $owner_state = self::DEFAULT_OWNER_STATE;
                        }

                        if ( !isset( $stack[ $owner_state ] ) ) {
                            $stack[ $owner_state ] = [];
                        }

                        $stack[ $owner_state ][] = [
                            'analysis_data_id' => $target[ 'product_analysis_data_id' ],
                            'target' => $target
                        ];
                    }
                } else {
                    $this->line( PHP_EOL . sprintf( '‼️ Skipping <bg=red;fg=white> %s </>, no valid product targets found for the current index and search parameters.', $product[ 'product' ][ 'name' ] ) );
                }
            }

            if ( !empty( $stack ) ) {
                $queue = [];

                foreach ( $stack as $state_code => $state ) {
                    $profile = [
                        'annuitant' => [
                            'state_cd' => $state_code,
                            'contract_cd' => false,
                            'premium' => number_format( $premium, 2, '.', '' ),
                            'purchase_date' => gmdate( 'Y-m-d' ),
                            'gender_cd_primary' => false,
                            'purchase_age_primary' => self::DEFAULT_PURCHASE_AGE,
                            'income_start_age_primary' => self::DEFAULT_PURCHASE_AGE + $deferral
                        ],
                        'targets' => []
                    ];

                    foreach ( $state as $row ) {
                        $profile[ 'targets' ][] = $row[ 'analysis_data_id' ];
                    }

                    $queue[] = $profile;
                }

                // truncate cache table
                //$this->line( sprintf( '  <bg=blue;fg=white> NOTICE </> Truncating cache table [%s]...', app( AnalysisGuaranteedCache::class )->getTable() ) . PHP_EOL );
                //DB::table( app( AnalysisGuaranteedCache::class )->getTable() )->truncate();

                // start analyzing
                foreach ( $queue as $profile ) {
                    // chunk
                    $chunks_total = ceil( ( count( $profile[ 'targets' ] ) / self::ANALYSIS_MAX_CHUNK_SIZE ) );
                    $chunks_position = 1;

                    foreach ( array_chunk( $profile[ 'targets' ], self::ANALYSIS_MAX_CHUNK_SIZE ) as $chunk ) {
                        $transaction_id = uuid_create();

                        $annuitant = array_merge(
                            $profile[ 'annuitant' ],
                            [
                                'contract_cd' => $marital_status,
                                'gender_cd_primary' => 'M'
                            ]
                        );

                        switch ( $marital_status ) {
                            case 'J' :
                                $annuitant[ 'gender_cd_joint' ] = 'F';
                                $annuitant[ 'purchase_age_joint' ] = $annuitant[ 'purchase_age_primary' ];
                                $annuitant[ 'income_start_age_joint' ] = $annuitant[ 'income_start_age_primary' ];
                                break;
                         }

                        // create profile and get quotes
                        $this->line( sprintf( '  <fg=white;bg=blue> NOTICE </> Deferral: %d, Premium: %f', ( intval( $annuitant[ 'income_start_age_primary' ] ) - intval( $annuitant[ 'purchase_age_primary' ] ) ), $annuitant[ 'premium' ] ) );

                        if ( $profile_id = CANNEXHelper::create_annuitant_profile( $transaction_id, $annuitant, $chunk ) ) {
                            $this->line( sprintf( '  <fg=white;bg=blue> NOTICE </> Profile ID created (%s / %s), marital status: %s.  Sending batch %d of %d...', $profile_id, $transaction_id, $marital_status, $chunks_position, $chunks_total ) . PHP_EOL );
                            $this->line( sprintf( '[+] Analysis IDs: %s', implode( ', ', $chunk ) ) . PHP_EOL );

                            $results = CANNEXHelper::get_guaranteed_rates( $profile_id, $transaction_id );

                            if ( !$results ) {
                                $this->line( '  <bg=red;fg=white> ERROR </> Rate query timed out, skipping...' . PHP_EOL );

                                continue;
                            }

                            // save to db
                            if ( ( isset( $results->income_request_data ) ) && ( isset( $results->income_response_data ) ) ) {
                                $result_deferral = intval( $results->income_request_data->income_start_age_primary ) - intval( $results->income_request_data->purchase_age_primary );

                                foreach ( $results->income_response_data as $result ) {
                                    if ( !isset( $result->income_error ) ) {
                                        $this->line( sprintf( '  <fg=white;bg=blue> NOTICE </> Sequence: %d, ID: %s, Premium: %f, Deferral: %d, Income (Init/High/Low): %f/%f/%f', $result->cnx_sequence_id, $result->analysis_data_id, $results->income_request_data->premium, $result_deferral, $result->income_data->initial_income, $result->income_data->highest_income, $result->income_data->lowest_income ) );

                                        $cache = new AnalysisGuaranteedCache;
                                        $cache->analysis_data_id = $result->analysis_data_id;
                                        $cache->deferral = $result_deferral;
                                        $cache->premium = number_format( floatval( $results->income_request_data->premium ), 2, '.', '' );
                                        $cache->owner_state = $results->income_request_data->state_cd;
                                        $cache->is_estimated_return = $result->cnx_sequence_id;
                                        $cache->is_joint = ( ( $results->income_request_data->contract_cd === 'J' ) ? true : false );
                                        $cache->income_initial = floatval( $result->income_data->initial_income );
                                        $cache->income_high = floatval( $result->income_data->highest_income );
                                        $cache->income_low = floatval( $result->income_data->lowest_income );
                                        $cache->save();
                                    }
                                }
                            }
                        } else {
                            $this->line( '  <bg=red;fg=white> ERROR </> Annuitant profile failed to be created, aborting...' . PHP_EOL );
                            break;
                        }

                        $chunks_position++;
                    }
                }
            }
        } else {
            $this->line( '  <bg=red;fg=white> ERROR </> No products matching the default investment profile found.' . PHP_EOL );

            return Command::FAILURE;
        }

        $this->line( PHP_EOL . '  <bg=cyan;fg=black> DONE! </>' . PHP_EOL );

        return Command::SUCCESS;
    }
}
