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
        public static $indexes = [];

        public static function identify_products( $index = false, $carrier = null, $strategy_type = null, $strategy_configuration = null, $calculation_frequency = null, $crediting_frequency = null, $guarantee_years = null, $guarantee_months = null, $participation_rate = null, $premium = null ) {
            $matches = [];

            if ( empty( self::$indexes ) ) {
                self::$indexes = Index::all();
            }

            $parameters_strategy = [];
            $parameters_rate = [];

            // strategy parameters
            if ( !empty( $strategy_type ) ) $parameters_strategy[ 'strategy_type' ] = $strategy_type;
            if ( !empty( $strategy_configuration ) ) $parameters_strategy[ 'strategy_configuration' ] = $strategy_configuration;
            if ( !empty( $calculation_frequency ) ) $parameters_strategy[ 'calculation_frequency' ] = $calculation_frequency;
            if ( !empty( $crediting_frequency ) ) $parameters_strategy[ 'crediting_frequency' ] = $crediting_frequency;
            if ( !empty( $guarantee_years ) ) $parameters_strategy[ 'guarantee_period_years' ] = $guarantee_years;
            if ( !empty( $guarantee_months ) ) $parameters_strategy[ 'guarantee_period_months' ] = $guarantee_months;

            // rate parameters
            if ( !empty( $participation_rate ) ) $parameters_rate[ 'current_participation_rate' ] = $participation_rate;

            $strategies = ProductsInstancesStrategy::where( $parameters_strategy );

            // index
            if ( !empty( $index ) ) $strategies->whereIn( 'index_id', $index );

            // carrier
            if ( !empty( $carrier ) ) $strategies->whereIn( 'product_instance_id', ProductsInstance::whereIn( 'product_id', CarriersProduct::whereIn( 'carrier_id', $carrier )->get()->pluck( 'product_id' )->toArray() )->get()->pluck( 'product_instance_id' )->toArray() );

            $strategies = $strategies->get();

            if ( $strategies->count() ) {
                $matches = ProductsInstancesStrategiesRate::with(
                    'strategy',
                    'strategy.instances',
                    'strategy.instances.product',
                    'strategy.instances.product.carrier',
                    'strategy.instances.product.carrier.ratings'
                )
                    ->whereIn( 'product_strategy_instance_id', $strategies->pluck( 'instance_id' ) )
                    ->where( $parameters_rate );

                if ( !empty( $premium ) ) {
                    $matches = $matches->where( 'premium_range_min', '<=', floatval( $premium ) )->where( 'premium_range_max', '>=', floatval( $premium ) );
                }

                $matches = $matches->get();
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
                                'name' => $instance->product->carrier->name,
                                'ratings' => $instance->product->carrier->ratings
                            ],
                            'targets' => []
                        ];
                    }

                    $analytics = Product::with( 'strategy' )
                        ->where( 'product_instance_id', $product->product_instance_id )
                        ->where( 'strategy_details_instance_id', $product->product_strategy_instance_id )
                        ->where( 'strategy_rate_instance_id', $product->instance_id )
                        ->where( 'analysis_cd', $type )
                        ->get();

                    if ( $analytics->count() ) {
                        foreach ( $analytics as $analysis ) {
                            $target = [
                                'product_analysis_cd' => $analysis->analysis_cd,
                                'product_analysis_data_id' => $analysis->analysis_data_id,
                                'rule_id' => $analysis->rule_id,
                                'product_id' => $analysis->product_id,
                                'product_profile_id' => $analysis->product_profile_id,
                                'product_instance_id' => $analysis->product_instance_id,
                                'product_strategy_details_instance_id' => $analysis->product_strategy_instance_id,
                                'product_strategy_rate_instance_id' => $analysis->instance_id,
                                'minimum_rate_instance_id' => $analysis->minimum_rate_instance_id,
                                'guarantee_period_months' => $analysis->guarantee_period_months,
                                'guarantee_period_years' => $analysis->guarantee_period_years,
                                'surrender_period_months' => $analysis->surrender_period_months,
                                'surrender_period_years' => $analysis->surrender_period_years,
                                'cdsc_schedule_instance_id' => $analysis->cdsc_schedule_instance_id,
                                'free_withdrawals_instance_id' => $analysis->free_withdrawals_instance_id,

                                'income_benefit_profile_id' => $analysis->income_benefit_profile_id,
                                'income_benefit_current_rider_fees_instance_id' => $analysis->income_benefit_current_rider_fees_instance_id,
                                'income_benefit_step_ups_instance_id' => $analysis->income_benefit_step_ups_instance_id,
                                'income_benefit_roll_ups_instance_id' => $analysis->income_benefit_roll_ups_instance_id,
                                'income_benefit_withdrawal_tables_instance_id' => $analysis->income_benefit_withdrawal_tables_instance_id,
                                'income_benefit_withdrawal_ruin_tables_instance_id' => $analysis->income_benefit_withdrawal_ruin_tables_instance_id,
                                'income_benefit_premium_bonus_instance_id' => $analysis->income_benefit_premium_bonus_instance_id,
                                'income_benefit_premium_multiplier_instance_id' => $analysis->income_benefit_premium_multiplier_instance_id,
                                'income_benefit_interest_crediting_instance_id' => $analysis->income_benefit_interest_crediting_instance_id,
                                'income_benefit_interest_bonus_crediting_instance_id' => $analysis->income_benefit_interest_bonus_crediting_instance_id,
                                'income_benefit_interest_multiplier_crediting_instance_id' => $analysis->income_benefit_interest_multiplier_crediting_instance_id,
                                'income_benefit_persistency_credit_instance_id' => $analysis->income_benefit_persistency_credit_instance_id,

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

                                // TODO: do we still need this?
                                'strategy' => $analysis->strategy,

                                'rules' => [
                                    'id' => $analysis->rule_id,
                                    'valid_states' => '', //implode( ',', $rules->where( 'rule_id', $analysis->rule_id )->get()->pluck( 'state_cd' )->toArray() )
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
