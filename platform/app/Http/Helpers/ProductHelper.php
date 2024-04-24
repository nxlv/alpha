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
            if ( !empty( $parameters[ 'carrier' ] ) ) {
                // restrict to carrier inventory
                $strategies->whereIn(
                    'product_instance_id',
                    ProductsInstance::whereIn(
                        'product_id',
                        CarriersProduct::whereIn(
                            'carrier_id',
                            $parameters[ 'carrier' ]
                        )->get()
                         ->pluck( 'product_id' )
                         ->toArray()
                    )->get()
                        ->pluck( 'product_instance_id' )
                        ->toArray()
                )->get()
                 ->pluck( 'product_instance_id' )
                 ->toArray();
            } else if ( !empty( $inventory ) ) {
                // restrict to saved inventory
                $strategies->whereIn(
                    'product_instance_id',
                    ProductsInstance::whereIn(
                        'product_id',
                        $inventory
                    )->get()
                        ->pluck( 'product_instance_id' )
                )->get()
                    ->pluck( 'product_instance_id' )
                    ->toArray();
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
                    $matches->where( 'premium_range_min', '<=', $parameters[ 'premium' ] )->where( 'premium_range_max', '>=', $parameters[ 'premium' ] );
                }

                $matches = $matches->get();

                error_log( 'identify_products: ' . $matches->count() . ' matches found.' );

                if ( $matches->count() ) {
                    // TODO: how do we handle age ranges for joint accounts? what if the joint person is outside the age range, but the owner is?
                    $rule_ids = Rule::whereIn( 'rule_id', RulesState::where( 'state_cd', $annuitant[ 'owner_state' ] )->get()->pluck( 'rule_id' )->toArray() )
                        ->where( 'age_range_min_years', '<=', $annuitant[ 'owner_age' ] )
                        ->where( 'age_range_max_years', '>=', $annuitant[ 'owner_age' ] )
                        //->where( 'premium_min', '<=', $parameters[ 'premium' ] )
                        ->where( 'premium_max', '>=', $parameters[ 'premium' ] )
                        ->where( 'contract', $annuitant[ 'annuity_type' ] )
                        ->get()->pluck( 'rule_id' )->toArray();

                    error_log( 'premium: ' . $parameters[ 'premium' ] );
                    error_log( 'identify_products: ' . count( $rule_ids ) . ' rulesets found' );

                    $products = Product::whereIn( 'rule_id', $rule_ids )
                        ->where( 'income_benefit_profile_id', '!=', '' )
                        ->whereIn( 'strategy_rate_instance_id', $matches->pluck( 'instance_id' )->toArray() )
                        ->get();

                    error_log( 'identify_products: ' . $products->count() . ' products found' );
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
