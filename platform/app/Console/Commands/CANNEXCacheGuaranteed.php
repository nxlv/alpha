<?php

namespace App\Console\Commands;

use App\Http\Helpers\ProductHelper;
use App\Http\Helpers\CANNEXHelper;
use App\Models\AnalysisGuaranteedCache;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CANNEXCacheGuaranteed extends Command {
    const DEFAULT_PREMIUM_BREAKPOINTS = [ '100' ];
    const DEFAULT_DEFERRAL_INTERVALS = [ 0, 5, 10, 20 ];
    const DEFAULT_OWNER_STATE = 'FL';
    const DEFAULT_MARITAL_STATUSES = [ 'S', 'J' ];
    const DEFAULT_ANALYSIS_SEQUENCES = [ 0, 1 ];
    const DEFAULT_PURCHASE_AGE = 50;
    const DEFAULT_AGE_RANGES = [ 50, 55, 60, 65, 70, 75 ];
    const ANALYSIS_MAX_CHUNK_SIZE = 1;  // 25, but the web service failes if ANY analysis data id is invalid for any reason, so we have to send one at a time :(

    protected $version = '1.0';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cannex:cache-guaranteed {--sequence=0} {--marital=S} {--premium=100.00} {--deferral=5} {--gender=M} {--owner-age=50} {--state=FL} {--batch=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Caches guaranteed income quotes for preset investment amounts across all products.';

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
        $owner_state = $this->option( 'state' );
        $gender = $this->option( 'gender' );
        $premium = preg_replace( '/[^0-9.]/', '', $this->option( 'premium' ) );
        $deferral = $this->option( 'deferral' );
        $sequence = $this->option( 'sequence' );
        $owner_age = $this->option( 'owner-age' );
        $batch = $this->option( 'batch' );

        if ( in_array( $sequence, self::DEFAULT_ANALYSIS_SEQUENCES ) === false ) {
            $this->line( '  <bg=red;fg=white> ERROR </> Invalid analysis (sequence) type.' . PHP_EOL );
            exit;
        }

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

        $time_start = microtime();

        $this->line( sprintf( '  <bg=blue;fg=white> CANNEX Cache Builder </> v%s', $this->version ) . PHP_EOL );
        $this->line( PHP_EOL . '  <fg=black;bg=blue> NOTICE </> Obtaining default product list...' . PHP_EOL );
        $this->line( sprintf( '  <fg=black;bg=green> CONFIG </> Marital status: %s', $marital_status ) );
        $this->line( sprintf( '  <fg=black;bg=green> CONFIG </> Premium: %f', $premium ) );
        $this->line( sprintf( '  <fg=black;bg=green> CONFIG </> Age: %f', $owner_age ) );
        $this->line( sprintf( '  <fg=black;bg=green> CONFIG </> State: %f', $owner_state ) );
        $this->line( sprintf( '  <fg=black;bg=green> CONFIG </> Deferral: %d', $deferral ) );
        $this->line( sprintf( '  <fg=black;bg=green> CONFIG </> Sequence: %d', $sequence ) );

        $products = ProductHelper::identify_products(
            [],
            [],
            [
                'annuity_type' => $marital_status,
                'owner_age' => $owner_age,
                'owner_state' => $owner_state,
                'joint_age' => null,
                'joint_state' => null
            ],
            [
                'premium' => $premium
            ]
        );

        if ( ( !empty( $products ) ) && ( $products->count() ) ) {
            $stack = [];

            $this->line( sprintf( '  <fg=black;bg=blue> NOTICE </> %d top-level product configurations queued.', $products->count() ) . PHP_EOL );

            foreach ( $products as $product ) {
                $this->line( PHP_EOL . sprintf( 'Adding <bg=yellow;fg=black> %s </>', $product->carrier_product->name ) );
                $this->line( sprintf( 'IBP: %s', $product->income_benefit->name ) );

                if ( !empty( $product->rules->valid_states ) ) {
                    $valid_states = $product->rules->valid_states->pluck( 'state_cd' )->toArray();

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
                    'analysis_data_id' => $product->analysis_data_id,
                ];
            }

            if ( !empty( $stack ) ) {
                $queue = [];

                $age_ranges = [];

                foreach ( self::DEFAULT_AGE_RANGES as $age ) {
                    if ( $owner_age ) {
                        if ( $age >= $owner_age ) {
                            $age_ranges[] = $age;
                        }
                    } else {
                        $age_ranges[] = $age;
                    }
                }

                foreach ( $stack as $state_code => $rows ) {
                    foreach ( $age_ranges as $age ) {
                        $profile = [
                            'annuitant' => [
                                'state_cd' => $state_code,
                                'contract_cd' => false,
                                'premium' => number_format( $premium, 2, '.', '' ),
                                'purchase_date' => gmdate( 'Y-m-d' ),
                                'gender_cd_primary' => false,
                                'purchase_age_primary' => $age,
                                'income_start_age_primary' => $age + $deferral
                            ],
                            'targets' => []
                        ];

                        foreach ( $rows as $row ) {
                            $profile[ 'targets' ][] = $row[ 'analysis_data_id' ];
                        }

                        $queue[] = $profile;
                    }
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
                        if ( ( $batch ) && ( $chunks_position < $batch ) ) {
                            $chunks_position++;
                            continue;
                        }

                        $batch = null;
                        $transaction_id = uuid_create();

                        $annuitant = array_merge(
                            $profile[ 'annuitant' ],
                            [
                                'contract_cd' => $marital_status,
                                'gender_cd_primary' => $gender
                            ]
                        );

                        switch ( $marital_status ) {
                            case 'J' :
                                $annuitant[ 'gender_cd_joint' ] = ( ( $gender === 'F' ) ? 'M' : 'F' );
                                $annuitant[ 'purchase_age_joint' ] = $annuitant[ 'purchase_age_primary' ];
                                $annuitant[ 'income_start_age_joint' ] = $annuitant[ 'income_start_age_primary' ];
                                break;
                         }

                        // create profile and get quotes
                        $this->line( sprintf( '  <fg=black;bg=blue> NOTICE </> Deferral: %d, Premium: %f', ( intval( $annuitant[ 'income_start_age_primary' ] ) - intval( $annuitant[ 'purchase_age_primary' ] ) ), $annuitant[ 'premium' ] ) );

                        if ( $profile_id = CANNEXHelper::create_annuitant_profile( $transaction_id, $annuitant, $sequence, $chunk ) ) {
                            $this->line( sprintf( '  <fg=black;bg=blue> NOTICE </> Profile ID created (%s / %s), marital status: %s.  Sending batch %d of %d...', $profile_id, $transaction_id, $marital_status, $chunks_position, $chunks_total ) . PHP_EOL );
                            $this->line( sprintf( '[+] Analysis IDs: %s', implode( ', ', $chunk ) ) . PHP_EOL );

                            $results = CANNEXHelper::get_guaranteed_rates( $profile_id, $transaction_id );

                            if ( !$results ) {
                                $this->line( '  <bg=red;fg=white> ERROR </> Rate query timed out, skipping...' . PHP_EOL );

                                continue;
                            }

                            // save to db
                            if ( ( isset( $results->income_request_data ) ) && ( isset( $results->income_response_data ) ) ) {
                                $result_deferral = intval( $results->income_request_data->income_start_age_primary ) - intval( $results->income_request_data->purchase_age_primary );

                                $this->line( sprintf( '[+] Deferral Period: %d', $result_deferral ) . PHP_EOL );

                                foreach ( ( ( !is_array( $results->income_response_data ) ) ? [ $results->income_response_data ] : $results->income_response_data ) as $result ) {
                                    if ( ( !isset( $result->income_error ) ) && ( isset( $result->cnx_sequence_id ) ) ) {
                                        $this->line( sprintf( '  <fg=black;bg=blue> NOTICE </> Sequence: %d, ID: %s, Premium: %f, Age: %d, Deferral: %d, Income (Init/High/Low): %f/%f/%f', $result->cnx_sequence_id, $result->analysis_data_id, $results->income_request_data->premium, $results->income_request_data->purchase_age_primary, $result_deferral, $result->income_data->initial_income, $result->income_data->highest_income, $result->income_data->lowest_income ) );

                                        $cache = new AnalysisGuaranteedCache;
                                        $cache->analysis_data_id = $result->analysis_data_id;
                                        $cache->deferral = $result_deferral;
                                        $cache->premium = number_format( floatval( $results->income_request_data->premium ), 2, '.', '' );
                                        $cache->owner_state = $results->income_request_data->state_cd;
                                        $cache->is_estimated_return = $result->cnx_sequence_id;
                                        $cache->is_joint = ( ( $results->income_request_data->contract_cd === 'J' ) ? true : false );
                                        $cache->purchase_age = floatval( $results->income_request_data->purchase_age_primary );
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
