<?php

    namespace App\Http\Helpers;

    use \App\Models\Index;
    use \App\Models\Product;
    use \App\Models\ProductsInstancesStrategy;
    use \App\Models\ProductsInstancesStrategiesRate;
    use \App\Models\Rule;
    use \App\Models\RulesState;

    class ProductHelper {
        public static $indexes = [];

        public static function identify_products( $index, $strategy_type = 'PP', $strategy_configuration = '03', $calculation_frequency = 'A', $crediting_frequency = 'A', $guarantee_years = 1, $guarantee_months = 0, $participation_rate = 100, $premium = null ) {
            $matches = [];

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

            if ( empty( self::$indexes ) ) {
                self::$indexes = Index::all();
            }

            if ( self::$indexes->where( 'index_id', $index )->count() ) {
                $strategies = ProductsInstancesStrategy::where( 'index_id', $index )
                                                       ->where( 'strategy_type', $strategy_type )
                                                       ->where( 'strategy_configuration', $strategy_configuration )
                                                       ->where( 'calculation_frequency', $calculation_frequency )
                                                       ->where( 'crediting_frequency', $crediting_frequency )
                                                       ->where( 'guarantee_period_years', $guarantee_years )
                                                       ->where( 'guarantee_period_months', $guarantee_months )
                                                       ->get();

                if ( $strategies->count() ) {
                    $matches = ProductsInstancesStrategiesRate::with( 'strategy', 'strategy.instances', 'strategy.instances.product', 'strategy.instances.product.carrier' )
                                                              ->where( 'current_participation_rate', $participation_rate )
                                                              ->whereIn( 'product_strategy_instance_id', $strategies->pluck( 'instance_id' ) );

                    if ( $premium ) {
                        $matches = $matches->where( 'premium_range_min', '<=', floatval( $premium ) )->where( 'premium_range_max', '>=', floatval( $premium ) );
                    }

                    $matches = $matches->get();
                }
            }

            if ( !empty( $matches ) ) {
                $matches = self::enumerate_products( $matches );
            }

            return $matches;
        }

        public static function enumerate_products( $products, $type = 'B' ) {
            $response = [];

            foreach ( $products as $product ) {
                if ( $product->strategy->instances->count() ) {
                    $instance = $product->strategy->instances->first();

                    if ( !isset( $response[ $instance->product->product_id ] ) ) {
                        $response[ $instance->product->product_id ] = [
                            'product' => [
                                'id' => $instance->product->product_id,
                                'name' => $instance->product->name,
                            ],
                            'carrier' => [
                                'id' => $instance->product->carrier->id,
                                'name' => $instance->product->carrier->name
                            ],
                            'targets' => []
                        ];
                    }

                    $analytics = Product::where( 'product_instance_id', $product->product_instance_id )
                        ->where( 'strategy_details_instance_id', $product->product_strategy_instance_id )
                        ->where( 'strategy_rate_instance_id', $product->instance_id )
                        ->where( 'analysis_cd', $type )
                        ->get();

                    if ( $analytics->count() ) {
                        foreach ( $analytics as $analysis ) {
                            $target = [
                                'product_analysis_cd' => $analysis->analysis_cd,
                                'product_analysis_data_id' => $analysis->analysis_data_id,
                                'product_strategy_details_instance_id' => $product->product_strategy_instance_id,
                                'product_strategy_rate_instance_id' => $product->instance_id,
                                'product_instance_id' => $product->product_instance_id,
                                'premium_range_min' => $product->premium_range_min,
                                'premium_range_max' => $product->premium_range_max,
                                'current_fixed_rate' => $product->current_fixed_rate,
                                'current_increase_fixed_rate' => $product->current_increase_fixed_rate,
                                'current_declared_rate' => $product->current_declared_rate,
                                'current_cap_rate' => $product->current_cap_rate,
                                'current_cap_rate_cd' => $product->current_cap_rate_cd,
                                'current_spread_rate' => $product->current_spread_rate,
                                'current_participation_rate' => $product->current_participation_rate,
                                'current_protection_buffer_rate' => $product->current_protection_buffer_rate,
                                'current_protection_floor_rate' => $product->current_protection_floor_rate,
                                'current_protection_downside_participation_rate' => $product->current_protection_downside_participation_rate,
                                'announced_fixed_rate' => $product->announced_fixed_rate,
                                'announced_declared_rate' => $product->announced_declared_rate,
                                'announced_cap_rate' => $product->announced_cap_rate,
                                'announced_cap_rate_cd' => $product->announced_cap_rate_cd,
                                'announced_spread_rate' => $product->announced_spread_rate,
                                'announced_participation_rate' => $product->announced_participation_rate,
                                'announced_protection_buffer_rate' => $product->announced_protection_buffer_rate,
                                'announced_protection_floor_rate' => $product->announced_protection_floor_rate,
                                'announced_protection_downside_participation_rate' => $product->announced_protection_downside_participation_rate,
                                'rules' => [
                                    'id' => $analysis->rule_id,
                                    'valid_states' => implode( ',', RulesState::where( 'rule_id', $analysis->rule_id )->get()->pluck( 'state_cd' )->toArray() )
                                ]
                            ];

                            $response[ $instance->product->product_id ][ 'targets' ][] = $target;
                        }
                    }
                }
            }

            return $response;
        }
    }
