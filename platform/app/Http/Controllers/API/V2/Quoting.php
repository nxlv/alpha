<?php

namespace App\Http\Controllers\API\V2;

use App\Models\ProductsInstancesStrategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ProductHelper;
use App\Http\Helpers\CANNEXHelper;

use App\Models\Product;
use App\Models\Index;
use App\Models\AnalysisGuaranteedCache;

class Quoting extends Controller {
    const YEARS_DEFERRAL_MIN = 5;
    const YEARS_DEFERRAL_MAX = 25;
    const PREMIUM_FAILSAFE = 10000;
    const INCOME_FAILSAFE = 100;

    public static $indexes = [];

    public function query_fixed( Request $request ) {
        $products = [];

        $method = $request->get( 'method', 'income' );
        $premium = preg_replace( '/[^0-9.]/', '', $request->get( 'premium' ) );
        $income = preg_replace( '/[^0-9.]/', '', $request->get( 'income' ) );
        $offset = $request->get( 'offset', 0 );
        $chunk_size = $request->get( 'chunk_size', 25 );

        // TODO: decide how we want to handle minimum premiums
        if ( $premium < self::PREMIUM_FAILSAFE ) {
            $premium = self::PREMIUM_FAILSAFE;
        }

        if ( $income < self::INCOME_FAILSAFE ) {
            $income = self::INCOME_FAILSAFE;
        }

        $annuitant = $request->get( 'annuitant' );
        $inventory = $request->get( 'inventory' );

        $parameters = [
            'method' => $method,
            'premium' => $premium,
            'income' => $income,
            'index_id' => $request->get( 'index' ),
            'carrier_id' => $request->get( 'carrier' )
        ];

        /*
         * Identify products
         */
        if ( !empty( $comparisons = $request->get( 'comparisons' ) ) ) {
            //$matches = ProductHelper::compare_products( $comparisons, $annuitant, $parameters );
        } else {
            $hash = 'alpha__fia-guaranteed-products-' . crc32( $method . '|' . $premium . '|' . $offset . '|' . $chunk_size ) . crc32( serialize( $parameters ) ) . crc32( serialize( $annuitant ) ) . crc32( serialize( $inventory ) );

            if ( Cache::has( $hash ) ) {
                $products = Cache::get( $hash );
            } else {
                $matches = ProductHelper::identify_products(
                    [
                        'strategy_type' => $request->get( 'strategy_type' ),
                        'strategy_configuration' => $request->get( 'strategy_configuration' ),
                        'calculation_frequency' => $request->get( 'calculation_frequency' ),
                        'crediting_frequency' => $request->get( 'crediting_frequency' ),
                        'guarantee_period_years' => $request->get( 'guarantee_period_years' ),
                        'guarantee_period_months' => $request->get( 'guarantee_period_months' )
                    ],
                    [
                        'current_participation_rate' => $request->get( 'participation_rate' ),
                    ],
                    $annuitant,
                    $parameters,
                    $inventory
                );

                if ( $matches->count() ) {
                    // TODO: Do we even need to query the Guaranteed Cache?  Review and see
                    $products = AnalysisGuaranteedCache::with(
                            [
                                'analysis.carrier_product',
                                'analysis.carrier_product.carrier',
                                'analysis.carrier_product.carrier.ratings',
                                'analysis.strategy',
                                'analysis.strategy.rates',
                                'analysis.income_benefit',
                                'analysis.income_benefit.rider_fee_current',
                                'analysis.income_benefit.income_start_age',
                                'analysis.income_benefit.premium_multiplier',
                                'analysis.income_benefit.premium_bonus',
                                'analysis.income_benefit.roll_up',
                                'analysis.income_benefit.step_up',
                            ]
                        )
                        ->whereIn( 'analysis_data_id', $matches->pluck( 'analysis_data_id' )->toArray() )
                        ->where( 'purchase_age', 65 )
                        ->where( 'deferral', 10 )
                        ->where( 'premium', 100 )
                        ->where( 'is_joint', ( ( $annuitant[ 'annuity_type' ] === 'J' ) ? true : false ) )
                        ->orderBy( 'income_initial', 'desc' )
                        ->offset( $offset )
                        ->limit( $chunk_size )
                        ->get();

                    Cache::add( $hash, $products, ( 60 * 10 ) );    // 10 minutes
                }
            }
        }

        return $products;
    }

    public function query_fixed_guaranteed( Request $request ) {
        $response = [];

        $products = $request->get( 'products' );
        $settings = $request->get( 'settings' );
        $annuitant = $request->get( 'annuitant' );

        $transaction_id = uuid_create();

        $parameters = [
            'state_cd' => $annuitant[ 'owner_state' ],
            'contract_cd' => $annuitant[ 'annuity_type' ],
            'premium' => preg_replace( '/[^0-9.]/', '', $settings[ 'premium' ] ),
            'purchase_date' => date( 'Y-m-d' ),
            'gender_cd_primary' => 'M',
            'purchase_age_primary' => $annuitant[ 'owner_age' ],
            'income_start_age_primary' => ( intval( $annuitant[ 'owner_age' ] ) + intval( $settings[ 'deferral' ] ) )
        ];

        if ( $annuitant[ 'annuity_type' ] === 'J' ) {
            $parameters[ 'gender_cd_joint' ] = 'F';
            $parameters[ 'purchase_age_joint' ] = date( 'Y-m-d' );
            $parameters[ 'income_start_age_joint' ] = ( intval( $annuitant[ 'joint_age' ] ) + intval( $settings[ 'deferral' ] ) );
        }

        // TODO: hash only fields that matter, not entire arrays, for better accuracy
        $hash = 'alpha__fia-guaranteed-' . crc32( serialize( $parameters ) ) . crc32( serialize( $annuitant ) ) . crc32( serialize( $settings ) ) . crc32( serialize( $products ) );

        if ( ( Cache::has( $hash ) ) && ( !empty( Cache::get( $hash ) ) ) ) {
            $response = Cache::get( $hash );
        } else {
            if ( $profile_id = CANNEXHelper::create_annuitant_profile( $transaction_id, $parameters, 0, $products ) ) {
                error_log( 'Profile created, ID#' . $profile_id );

                $results = CANNEXHelper::get_guaranteed_rates( $profile_id, $transaction_id );

                if ( ( isset( $results->income_request_data ) ) && ( isset( $results->income_response_data ) ) ) {
                    if ( !is_array( $results->income_response_data ) ) {
                        // TODO
                    } else {
                        foreach ( $results->income_response_data as $result ) {
                            $response[] = $result;
                        }
                    }
                }
            } else {
                error_log( 'Failed to create annuitant profile' );
            }

            if ( !empty( $response ) ) {
                Cache::add( $hash, $response, ( 60 * 10 ) ); // 10 minutes
            }
        }

        return $response;
    }

    public function query_fixed_backtested_return( Request $request ) {
        $response = [];

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

        $products = $request->get( 'products' );
        $settings = $request->get( 'settings' );
        $annuitant = $request->get( 'annuitant' );

        $deferral = $settings[ 'deferral' ];
        $premium = preg_replace( '/[^0-9.]/', '', $settings[ 'premium' ] );

        if ( !empty( $products ) ) {
            $queue = [];

            $analyses = Product::whereIn( 'analysis_data_id', $products )->get();

            if ( $analyses->count() ) {
                foreach ( $products as $product ) {
                    $index_date_oldest = null;
                    $index_date_newest = null;
                    $index_id = null;

                    foreach ( $analyses as $analysis ) {
                        if ( $analysis->analysis_data_id === $product ) {
                            $index_id = ProductsInstancesStrategy::where( 'instance_id', $analysis->strategy_details_instance_id )->get()->pluck( 'index_id' );

                            if ( $index_id->count() ) {
                                $index_id = $index_id->first();

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

                            break;
                        }
                    }

                    if ( ( !empty( $index_id ) ) && ( !empty( $index_date_oldest ) ) && ( ( !empty( $index_date_newest ) ) ) ) {
                        $index_data_limits = ProductHelper::validate_index_dates( $index_id, strtotime( $index_date_oldest ), strtotime( $index_date_newest ), time(), $deferral );

                        $queue[] = [
                            'contract_cd'                 => $annuitant[ 'annuity_type' ],
                            'premium'                     => $premium,
                            'purchase_date'               => gmdate( 'Y-m-d\TH:i:s.v\Z' ),
                            'gender_cd_primary'           => $annuitant[ 'owner_gender' ],
                            'gender_cd_joint'             => null,
                            'purchase_age_primary'        => $annuitant[ 'owner_age' ],
                            'purchase_age_joint'          => null,
                            'income_start_age_primary'    => ( intval( $annuitant[ 'owner_age' ] ) + intval( $index_data_limits[ 'deferral' ] ) ),
                            'income_start_age_joint'      => null,
                            'index_time_range'            => array(
                                'start_month' => $index_data_limits[ 'index_date_end' ]->format( 'n' ),
                                'start_year' => $index_data_limits[ 'index_date_end' ]->format( 'Y' ),
                                'end_month' => $index_data_limits[ 'index_date_start' ]->format( 'n' ),
                                'end_year' => $index_data_limits[ 'index_date_start' ]->format( 'Y' )
                            ),
                            'anty_ds_version_id'          => CANNEXHelper::ANTY_ANLY_VERSION_ID,
                            'analysis_cd'                 => 'B',
                            'analysis_data_id'            => $product,
                            'analysis_time_horizon_years' => $index_data_limits[ 'deferral' ] + 1,
                            'is_test'                     => 'N'
                        ];
                    }
                }
            }

            if ( !empty( $queue ) ) {
                $response = CANNEXHelper::analyze_fixed( $queue );
            }
        }

        return $response;
    }
}
