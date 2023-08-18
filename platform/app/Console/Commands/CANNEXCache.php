<?php

namespace App\Console\Commands;

use App\Http\Helpers\ProductHelper;
use App\Http\Helpers\CANNEXHelper;
use App\Models\AnalysisCache;
use App\Models\Index;
use App\Models\Rule;
use App\Models\RulesState;
use App\Models\Product;
use App\Models\ProductsInstancesStrategy;
use App\Models\ProductsInstancesStrategiesRate;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CANNEXCache extends Command {
    const KEY_RECOVERY_ID = 'cannex_cli_cache_last_id';

    //const DEFAULT_PREMIUM_BREAKPOINTS = [ '50000', '100000', '250000', '500000', '1000000' ];
    const DEFAULT_PREMIUM_BREAKPOINTS = [ '50000', '100000', '250000', '500000' ];
    //const DEFAULT_DEFERRAL_INTERVALS = [ 5, 10, 20 ];
    const DEFAULT_DEFERRAL_INTERVALS = [ 5, 10, 20 ];
    const DEFAULT_OWNER_STATE = 'FL';
    const ANALYSIS_MAX_CHUNK_SIZE = 20;

    protected $version = '1.0';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cannex:cache {--all} {--restart}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Caches real quotes for preset investment amounts across all products.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $_param_fetchall = $this->option( 'all' );
        $_param_restart = $this->option( 'restart' );
        $_param_recovery = ( ( $_param_restart ) ? null : Cache::store( 'file' )->get( self::KEY_RECOVERY_ID ) );

        if ( Storage::exists( 'banner-cannex.asc' ) ) {
            $this->line( '<fg=blue>' . Storage::get( 'banner-cannex.asc' ) . '</>' );
        }

        $this->line( sprintf( '  <bg=blue;fg=white> CANNEX Cache Builder </> v%s', $this->version ) . PHP_EOL );

        if ( $_param_fetchall ) {
            $this->line( PHP_EOL . '  <fg=white;bg=blue> NOTICE </> Request received to cache ALL products.' . PHP_EOL );

            $products = ProductHelper::identify_products( false );
        } else {
            $this->line( PHP_EOL . '  <fg=white;bg=blue> NOTICE </> Caching default profile' . PHP_EOL );

            /**
             * Default FIA profile
             * - index_id = (lookup: S&P 500)
             * - strategy_type = 'PP' (Point-to-Point)
             * - strategy_configuration = '03' (Cap + Participation) (lookup: Participation 100%)
             * - calculation_frequency = 'A' (Annual)
             * - crediting_frequency = 'A' (Annual)
             * - guarantee_period_years = 1
             * - guarantee_period_months = 0
             */

            $index_id = Index::where( 'index_name', 'S&P 500' )->get()->first()->index_id;
            $products = ProductHelper::identify_products( $index_id );
        }

        if ( ( !empty( $products ) ) && ( count( $products ) ) ) {
            $stack = [];

            $this->line( sprintf( '  <fg=white;bg=blue> NOTICE </> %d top-level products queued.', count( $products ) ) . PHP_EOL );

            if ( !empty( $_param_recovery ) ) {
                $this->line( sprintf( '  <bg=yellow;fg=black> RECOVERY </> Recovering from product ID %s (crash recovery)', $_param_recovery ) . PHP_EOL );
            }

            foreach ( $products as $product ) {
                $this->line( PHP_EOL . sprintf( 'Adding <bg=yellow;fg=black> %s </>', $product[ 'product' ][ 'name' ] ) );

                // $product[ 'targets' ]
                // - look at premium ranges
                //   - if 0 to 999999, then query $50k, $100k, $250k, $500k
                //   - if range, then query low range and high range (if zero, ignore)
                // - save with 10 & 20 deferrals
                if ( count( $product[ 'targets' ] ) ) {
                    // let's build an array of target premium amounts we want to request and cache,
                    // but we need to make sure we stay within the min/max bounds of this particular strategy.
                    // this is so we don't continually ask CANNEX for an illustration of a product with a premium that is out of min/max range for this strategy
                    foreach ( $product[ 'targets' ] as $target ) {
                        $target_premium_min = floatval( $target[ 'premium_range_min' ] );
                        $target_premium_max = floatval( $target[ 'premium_range_max' ] );

                        // save originals in case we override
                        $_target_premium_min = $target_premium_min;
                        $_target_premium_max = $target_premium_max;

                        if ( ( $target_premium_min === 0.00 ) && ( $target_premium_max >= 99999999 ) ) {
                            // wide open premium, just add in the default breakpoints
                            $target_premiums = self::DEFAULT_PREMIUM_BREAKPOINTS;
                        } else {
                            if ( $target_premium_min === 0.00 ) {
                                $target_premium_min = 25000;
                            }

                            if ( $target_premium_max >= 99999999 ) {
                                $target_premium_max = $target_premium_min * 2;
                            }

                            // TODO: maybe add in some additional targets in between the min/max?
                            $target_premiums = [ $target_premium_min, $target_premium_max ];

                            // add in any defaults that don't match this target
                            foreach ( self::DEFAULT_PREMIUM_BREAKPOINTS as $breakpoint ) {
                                if ( ( $breakpoint >= $_target_premium_min ) && ( $breakpoint <= $_target_premium_max ) ) {
                                    if ( array_search( $breakpoint, $target_premiums ) === false ) {
                                        $target_premiums[] = $breakpoint;
                                    }
                                }
                            }
                        }

                        sort( $target_premiums );

                        if ( !empty( $target_premiums ) ) {
                            $this->line( sprintf( '* ADID: <bg=gray;fg=white> %s </> 🎯 Instance Premium Range: <bg=green;fg=black> %d ➡️ %d </> 🎯 Premiums: <bg=cyan;fg=black> %s </> 🎯 Deferral Periods: <bg=cyan;fg=black> %s </>', $target[ 'product_analysis_data_id' ], $target[ 'premium_range_min' ], $target[ 'premium_range_max' ], implode( ', ', $target_premiums ), implode( ', ', self::DEFAULT_DEFERRAL_INTERVALS ) ) );
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

                            foreach ( $target_premiums as $premium ) {
                                // TODO: check index start date
                                foreach ( self::DEFAULT_DEFERRAL_INTERVALS as $interval ) {
                                    $stack[] = [
                                        'premium' => $premium,
                                        'deferral' => $interval,
                                        'owner_state' => $owner_state,
                                        'target' => $target
                                    ];
                                }
                            }
                        }
                    }
                } else {
                    $this->line( PHP_EOL . sprintf( '‼️ Skipping <bg=red;fg=white> %s </>, no valid product targets found for the current index and search parameters.', $product[ 'product' ][ 'name' ] ) );
                }
            }

            if ( !empty( $stack ) ) {
                $queue = [];

                foreach ( $stack as $row ) {
                    if ( empty( $row[ 'target' ] ) ) {
                        continue;
                    }

                    if ( !empty( $_param_recovery ) ) {
                        if ( $row[ 'target' ][ 'product_analysis_data_id' ] === $_param_recovery ) {
                            $this->line( sprintf( '  <bg=yellow;fg=black> RECOVERY </> Recovery product ID (%s) FOUND within this product.', $_param_recovery ) . PHP_EOL );

                            $_param_recovery = null;
                        } else {
                            $this->line( sprintf( '  <bg=yellow;fg=black> RECOVERY </> Recovery product ID (%s) not found within this product. Skipping product...', $_param_recovery ) . PHP_EOL );
                            continue;
                        }
                    }

                    $queue[] = CANNEXHelper::build_analysis_request(
                        $row[ 'target' ][ 'product_analysis_data_id' ],
                        [
                            'premium' => $row[ 'premium' ],
                            'deferral' => $row[ 'deferral' ],
                            'owner_state' => $row[ 'owner_state' ],
                            'analysis_cd' => $row[ 'target' ][ 'product_analysis_cd' ]
                        ]
                    );
                }

                // build cache
                $this->line( PHP_EOL . sprintf( '  <bg=yellow;fg=black> %d </> product variations to analyze. <bg=blue;fg=white> %d </> requests will be made.', count( $stack ), ceil( count( $queue ) / self::ANALYSIS_MAX_CHUNK_SIZE ) ) . PHP_EOL );

                foreach ( array_chunk( $queue, 20 ) as $chunk ) {
                    $analyses = CANNEXHelper::analyze_fixed( $chunk );

                    foreach ( $analyses as $analysis ) {
                        if ( property_exists( $analysis, 'analysis_error' ) ) {
                            $this->line(
                                PHP_EOL .
                                sprintf(
                                    '  <bg=red;fg=white> ERROR </> Analysis for <bg=blue;fg=white> %s </> failed. <bg=gray;fg=white> QUERY </> $%d, %d year deferral, analysis code %s%s  <bg=red;fg=white> WHY   </> %s [%s]',
                                    $analysis->analysis_request->analysis_data_id,
                                    $analysis->analysis_request->premium,
                                    ( intval( $analysis->analysis_request->income_start_age_primary ) - intval( $analysis->analysis_request->purchase_age_primary ) ),
                                    $analysis->analysis_request->analysis_cd,
                                    PHP_EOL,
                                    $analysis->analysis_error->error_message,
                                    $analysis->analysis_error->error_cd
                                )
                            );
                        } else if ( property_exists( $analysis, 'analysis_data' ) ) {
                            foreach ( $analysis->analysis_data as $analysis_year ) {
                                if ( floatval( $analysis_year->income ) ) {
                                    // income
                                    $this->line(
                                        PHP_EOL .
                                        sprintf(
                                            '  <bg=green;fg=black> SUCCESS </> Saving cached data: ADID: <bg=gray;fg=white> %s </> Premium: <bg=green;fg=black> $%d </> Income: <bg=green;fg=black> $%d </> Deferral: <bg=green;fg=black> %d </>',
                                            $analysis->analysis_request->analysis_data_id,
                                            $analysis->analysis_request->premium,
                                            $analysis_year->income,
                                            ( $analysis_year->year - 1 )
                                        )
                                    );

                                    $cache = new AnalysisCache;
                                    $cache->analysis_data_id = $analysis->analysis_request->analysis_data_id;
                                    $cache->premium = floatval( $analysis->analysis_request->premium );
                                    $cache->income = floatval( $analysis_year->income );
                                    $cache->deferral = ( $analysis_year->year - 1 );
                                    $cache->save();

                                    Cache::store( 'file' )->set( self::KEY_RECOVERY_ID, $analysis->analysis_request->analysis_data_id );
                                    break;
                                }
                            }
                        }
                    }

                    $this->line( PHP_EOL . '  <bg=gray;fg=white> SLEEP </> Sleeping 3 seconds before processing next chunk...' . PHP_EOL );

                    sleep( 3 );
                }
            }
        } else {
            $this->line( '  <bg=red;fg=white> ERROR </> No products matching the default investment profile found.' . PHP_EOL );

            return Command::FAILURE;
        }

        $this->line( PHP_EOL . '  <bg=cyan;fg=black> DONE! </>' . PHP_EOL );

        // clear recovery cache id
        Cache::store( 'file' )->delete( self::KEY_RECOVERY_ID );

        return Command::SUCCESS;
    }
}
