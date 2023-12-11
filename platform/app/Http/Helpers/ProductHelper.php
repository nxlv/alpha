<?php

    namespace App\Http\Helpers;

    use App\Models\CarriersProduct;
    use App\Models\Index;
    use App\Models\Product;
    use App\Models\ProductsInstance;
    use App\Models\ProductsInstancesStrategy;
    use App\Models\ProductsInstancesStrategiesRate;
    use App\Models\Rule;
    use App\Models\RulesState;

    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\DB;

    class ProductHelper {
        public static $indexes = [];

        public static function identify_products( $params_strategy = [], $params_rate = [], $annuitant = [], $parameters = [], $inventory = [] ) {
            $products = false;

            foreach ( $params_strategy as $param_key => $param_value ) {
                if ( !empty( $params_strategy[ $param_key ] ) ) {
                    $params_strategy[ $param_key ] = $param_value;
                } else {
                    unset( $params_strategy[ $param_key ] );
                }
            }

            foreach ( $params_rate as $param_key => $param_value ) {
                if ( !empty( $params_rate[ $param_key ] ) ) {
                    $params_rate[ $param_key ] = $param_value;
                } else {
                    unset( $params_rate[ $param_key ] );
                }
            }

            $strategies = ProductsInstancesStrategy::where( $params_strategy );

            // inventory
            if ( !empty( $inventory ) ) {
                // restrict to saved inventory
                $strategies->whereIn( 'product_instance_id', ProductsInstance::whereIn( 'product_id', $inventory )->get()->pluck( 'product_instance_id' )->toArray() );
            } else if ( !empty( $parameters[ 'carrier' ] ) ) {
                // restrict to carrier inventory
                $strategies->whereIn( 'product_instance_id', ProductsInstance::whereIn( 'product_id', CarriersProduct::whereIn( 'carrier_id', $parameters[ 'carrier' ] )->get()->pluck( 'product_id' )->toArray() )->get()->pluck( 'product_instance_id' )->toArray() );
            }

            // index
            if ( !empty( $parameters[ 'index' ] ) ) {
                // restrict to index
                $strategies->whereIn( 'index_id', $parameters[ 'index' ] );
            }

            $strategies = $strategies->get();

            if ( $strategies->count() ) {
                $matches = ProductsInstancesStrategiesRate::whereIn( 'product_strategy_instance_id', $strategies->pluck( 'instance_id' ) )
                    ->where( $params_rate );

                if ( !empty( $parameters[ 'premium' ] ) ) {
                    $matches->where( 'premium_range_min', '<=', floatval( $parameters[ 'premium' ] ) )->where( 'premium_range_max', '>=', floatval( $parameters[ 'premium' ] ) );
                }

                $matches = $matches->get();

                if ( $matches->count() ) {
                    $rule_ids = Rule::whereIn( 'rule_id', RulesState::where( 'state_cd', $annuitant[ 'owner_state' ] )->get()->pluck( 'rule_id' )->toArray() )
                        ->where( 'age_range_min_years', '<=', $annuitant[ 'owner_age' ] )
                        ->where( 'age_range_max_years', '>=', $annuitant[ 'owner_age' ] )
                        ->where( 'premium_min', '<=', $parameters[ 'premium' ] )
                        ->where( 'premium_max', '>=', $parameters[ 'premium' ] )
                        ->whereIn( 'contract', [ '', $annuitant[ 'annuity_type' ] ] )
                        ->get()->pluck( 'rule_id' )->toArray();

                    $products = Product::whereIn( 'rule_id', $rule_ids )
                        ->whereIn( 'strategy_rate_instance_id', $matches->pluck( 'instance_id' )->toArray() )
                        ->get();
                }
            }

            return $products;
        }

        public static function compare_products( $analysis_ids, $annuitant, $parameters ) {
            $matches = false;

            if ( !empty( $analysis_ids ) ) {
                $matches = Product::whereIn( 'analysis_data_id', $analysis_ids )->get();
            }

            return $matches;
        }

        public static function enumerate_products( $products, $annuitant, $parameters, $analysis_ids = [] ) {
            $response = [];

            error_log( 'ENUMERATING' );

            // rules
            $rule_ids = Rule::whereIn( 'rule_id', RulesState::where( 'state_cd', $annuitant[ 'owner_state' ] )->get()->pluck( 'rule_id' )->toArray() )
                ->where( 'age_range_min_years', '<=', $annuitant[ 'owner_age' ] )
                ->where( 'age_range_max_years', '>=', $annuitant[ 'owner_age' ] )
                ->where( 'premium_min', '<=', $parameters[ 'premium' ] )
                ->where( 'premium_max', '>=', $parameters[ 'premium' ] )
                ->whereIn( 'contract', [ '', $annuitant[ 'annuity_type' ] ] )
                ->get()->pluck( 'rule_id' )->toArray();

            error_log( 'RULES FETCHED' );

            // products
            error_log( 'START: FETCHING MASTER PRODUCT LIST' );

            $analyses = Product::with(
                'carrier_product',
                'carrier_product.carrier',
                'carrier_product.carrier.ratings',
                'strategy',
                'strategy.rates',
                'income_benefit',
                'income_benefit.rider_fee_current',
                'income_benefit.premium_multiplier',
                'income_benefit.premium_bonus',
                'income_benefit.roll_up',
                'income_benefit.interest_crediting',
                'income_benefit.interest_bonus_crediting',
                'income_benefit.interest_multiplier_crediting',
                'income_benefit.income_start_age'
            )
                /*
                ->whereIn( 'product_instance_id', array_map( function( $element ) { return $element->product_instance_id; }, $products->toArray() ) )
                ->whereIn( 'strategy_details_instance_id', array_map( function( $element ) { return $element->product_strategy_instance_id; }, $products->toArray() ) )
                */
                ->whereIn( 'strategy_rate_instance_id', $products->pluck( 'instance_id' )->toArray() )
                ->where( 'analysis_cd', 'B' );

            // restrict to specific analysis_data_id's for compare mode?
            if ( !empty( $analysis_ids ) ) {
                $analyses->whereIn( 'analysis_data_id', $analysis_ids );

                // TODO: we don't really need rules on comparison mode, since the products added to compare
            } else {
                $analyses->whereIn( 'rule_id', $rule_ids );
            }

            $analyses = $analyses->get();

            if ( $analyses->count() ) {
                foreach ( $analyses as $analysis ) {
                    if ( !isset( $response[ $analysis->product_id ] ) ) {
                        $response[ $analysis->product_id ] = [
                            'product' => [
                                'id' => $analysis->product_id,
                                'name' => $analysis->carrier_product->name,
                            ],
                            'carrier' => [
                                'id' => $analysis->carrier_product->carrier->id,
                                'name' => $analysis->carrier_product->carrier->name,
                                'ratings' => $analysis->carrier_product->carrier->ratings
                            ],
                            'targets' => []
                        ];
                    }

                    $target = [
                        // important id's
                        'product_analysis_cd' => $analysis->analysis_cd,
                        'product_analysis_data_id' => $analysis->analysis_data_id,
                        'rule_id' => $analysis->rule_id,
                        'product_id' => $analysis->product_id,
                        'product_profile_id' => $analysis->product_profile_id,
                        'product_instance_id' => $analysis->product_instance_id,
                        'product_strategy_details_instance_id' => $analysis->product_strategy_instance_id,
                        'product_strategy_rate_instance_id' => $analysis->instance_id,

                        // guarantee period metrics
                        'guarantee_period_months' => $analysis->guarantee_period_months,
                        'guarantee_period_years' => $analysis->guarantee_period_years,
                        'surrender_period_months' => $analysis->surrender_period_months,
                        'surrender_period_years' => $analysis->surrender_period_years,

                        // premium range
                        'premium_range_min' => $analysis->strategy->rates->premium_range_min,
                        'premium_range_max' => $analysis->strategy->rates->premium_range_max,

                        // current metrics
                        'current_fixed_rate' => $analysis->strategy->rates->current_fixed_rate,
                        'current_increase_fixed_rate' => $analysis->strategy->rates->current_increase_fixed_rate,
                        'current_declared_rate' => $analysis->strategy->rates->current_declared_rate,
                        'current_cap_rate' => $analysis->strategy->rates->current_cap_rate,
                        'current_cap_rate_cd' => $analysis->strategy->rates->current_cap_rate_cd,
                        'current_spread_rate' => $analysis->strategy->rates->current_spread_rate,
                        'current_participation_rate' => $analysis->strategy->rates->current_participation_rate,
                        'current_protection_buffer_rate' => $analysis->strategy->rates->current_protection_buffer_rate,
                        'current_protection_floor_rate' => $analysis->strategy->rates->current_protection_floor_rate,
                        'current_protection_downside_participation_rate' => $analysis->strategy->rates->current_protection_downside_participation_rate,

                        // exported relations
                        'strategy' => $analysis->strategy,
                        'income_benefit' => $analysis->income_benefit
                    ];

                    $response[ $analysis->product_id ][ 'targets' ][] = $target;

                    error_log( 'ANALYSIS: END' );
                }
            }

            return $response;
        }

        public static function validate_index_dates( $analysis_data_id, $purchase_date, $deferral ) {
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

            // find index dates
            $index_id = ProductsInstancesStrategy::where( 'instance_id', Product::where( 'analysis_data_id', $analysis_data_id )->get()->pluck( 'strategy_details_instance_id' )->first() )->get()->pluck( 'index_id' )->first();
            $index_date_oldest = null;
            $index_date_newest = null;

            foreach ( self::$indexes as $index ) {
                if ( $index->index_id === $index_id ) {
                    $index_date_oldest = $index->oldest_date;
                    $index_date_newest = $index->most_recent_date;
                    break;
                }
            }

            $_deferral = $deferral;

            $date_oldest = new \DateTime( sprintf( '@%d', strtotime( $index_date_oldest ) ) );
            $date_newest = new \DateTime( sprintf( '@%d', strtotime( $index_date_newest ) ) );
            $date_purchase = new \DateTime( sprintf( '@%d', strtotime( $purchase_date ) ) );

            $date_start = new \DateTime( $date_purchase->format( 'Y-m-d' ) );

            $deferral_max = intval( $date_oldest->diff( $date_newest )->format( '%y' ) );

            if ( $deferral > $deferral_max ) {
                $deferral = $deferral_max;
            }

            if ( $date_start > $date_newest ) {
                $date_start = new \DateTime( $date_newest->format( 'Y-m-d' ) );
            }

            $date_end = clone $date_start;
            $date_end = $date_end->sub( \DateInterval::createFromDateString( sprintf( '%d years', $deferral ) ) );

            return [
                'deferral_original' => $_deferral,
                'deferral' => $deferral,
                'deferral_max' => $deferral_max,
                'index_date_start' => $date_start,
                'index_date_end' => $date_end
            ];
        }
    }
