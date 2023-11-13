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

    use Illuminate\Support\Facades\DB;

    class ProductHelper {
        public static function identify_products( $params_strategy = [], $params_rate = [], $annuitant = [], $parameters = [], $inventory = [] ) {
            $products = [];

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
                $strategies->whereIn( 'product_instance_id', ProductsInstance::whereIn( 'product_id', CarriersProduct::whereIn( 'carrier_id', $parameters[ 'carrier_id' ] )->get()->pluck( 'product_id' )->toArray() )->get()->pluck( 'product_instance_id' )->toArray() );
            }

            // index
            if ( !empty( $parameters[ 'index' ] ) ) {
                // restrict to index
                $strategies->where( 'index_id', $parameters[ 'index' ] );
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
            $matches = [];

            if ( !empty( $analysis_ids ) ) {
                $analyses = Product::whereIn( 'analysis_data_id', $analysis_ids )->get();

                $matches = ProductsInstancesStrategiesRate::with(
                    'strategy',
                    'strategy.instances',
                    'strategy.instances.product',
                    'strategy.instances.product.carrier',
                    'strategy.instances.product.carrier.ratings'
                )
                    ->whereIn( 'instance_id', $analyses->pluck( 'strategy_rate_instance_id' ) );

                $matches = $matches->get();
            }

            if ( !empty( $matches ) ) {
                $matches = self::enumerate_products( $matches, $annuitant, $parameters, $analysis_ids );
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

            error_log( 'END: FETCHING MASTER PRODUCT LIST (COUNT: ' . $analyses->count() . ')' );

            if ( $analyses->count() ) {
                foreach ( $analyses as $analysis ) {
                    error_log( 'ANALYSIS: START' );

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
    }
