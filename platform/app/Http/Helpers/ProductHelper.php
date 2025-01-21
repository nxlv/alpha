<?php

    namespace App\Http\Helpers;

    use App\Models\AnalysisGuaranteedCache;
    use App\Models\CarriersProduct;
    use App\Models\CarriersRating;
    use App\Models\IncomeBenefit;
    use App\Models\Index;
    use App\Models\Notice;
    use App\Models\Product;
    use App\Models\ProductsInstance;
    use App\Models\ProductsInstancesStrategiesFeesCurrent;
    use App\Models\ProductsInstancesStrategy;
    use App\Models\ProductsInstancesStrategiesRate;
    use App\Models\Rule;
    use App\Models\RulesState;

    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;

    class ProductHelper {
        public static $filters = [ 'index', 'carrier', 'rating_ambest', 'surrender_years', 'rider_type', 'spread', 'cap', 'participation', 'free_withdrawal', 'index_age', 'rate_guarantee_type', 'performance_trigger_type' ];
        public static $indexes = [];

        public static function find_text_id_by_keywords( $keywords ) {
            if ( !empty( $keywords ) ) {
                if ( !is_array( $keywords ) ) {
                    $keywords = [ $keywords ];
                }

                $keyword_counter = 0;
                $results = null;

                foreach ( $keywords as $keyword ) {
                    if ( $keyword_counter === 0 ) {
                        $results = \App\Models\Notice::where( 'notice', 'LIKE', sprintf( '%%%s%%', $keyword ) );
                    } else {
                        $results = $results->orWhere( 'notice', 'LIKE', sprintf( '%%%s%%', $keyword ) );
                    }
                }

                $results = $results->get();
            }

            return $results;
        }

        public static function get_products_with_filter( $filter_key, $filter_value ) {
            $results = [];

            switch ( $filter_key ) {
                case 'index' :
                    $results = ProductsInstancesStrategy::whereIn( 'index_id', $filter_value )->get()->pluck( 'product_instance_id' )->toArray();
                    break;

                case 'carrier' :
                    $results = ProductsInstancesStrategy::whereIn( 'product_instance_id',
                        ProductsInstance::whereIn( 'product_id',
                            CarriersProduct::whereIn( 'carrier_id', $filter_value )->get()->pluck( 'product_id' )->toArray()
                        )->get()->pluck( 'product_instance_id' )->toArray()
                    )->get()->pluck( 'product_instance_id' )->toArray();
                    break;

                case 'rating_ambest' :
                    $results = Product::whereIn( 'product_id',
                        CarriersProduct::whereIn( 'carrier_id',
                            CarriersRating::where( 'company', '=', 'AMB' )->whereIn( 'rating', $filter_value )->get()->pluck( 'carrier_id' )->toArray()
                        )->get()->pluck( 'product_id' )->toArray()
                    )->get()->pluck( 'product_instance_id' )->toArray();
                    break;

                case 'surrender_years' :
                    if ( intval( $filter_value ) >= 0 ) {
                        $results = Product::where( 'surrender_period_years', '>=', intval( $filter_value ) )->get()->pluck( 'product_instance_id' )->toArray();
                    }
                    break;

                case 'bonus' :
                    // bonus
                    $results = Product::whereIn( 'income_benefit_profile_id',
                        IncomeBenefit::where( 'premium_bonus_text_id', ( ( $filter_value === 'income' ) ? '=' : '!=' ), '' )->get()->pluck( 'income_benefit_profile_id' )->toArray()
                    )->get()->pluck( 'product_instance_id' )->toArray();
                    break;

                case 'cap' :
                    $results = ProductsInstancesStrategiesRate::where( 'current_cap_rate', '>=', $filter_value )->get()->pluck( 'product_instance_id' )->toArray();
                    break;

                case 'spread' :
                    $results = ProductsInstancesStrategiesRate::where( 'current_spread_rate', '>=', $filter_value )->get()->pluck( 'product_instance_id' )->toArray();
                    break;

                case 'participation' :
                    $results = ProductsInstancesStrategiesRate::where( 'current_participation_rate', '>=', $filter_value )->get()->pluck( 'product_instance_id' )->toArray();
                    break;

                case 'free_withdrawal' :
                    // TODO: no matter what we do here, CANNEX data shows all products have free withdrawals, which can't be true, so this needs to be revisited
                    break;

                case 'rider_type' :
                    switch ( $filter_value ) {
                        case 'income_increasing' :
                            $results = Product::whereIn( 'income_benefit_profile_id',
                                IncomeBenefit::where( 'name', 'LIKE', '%increas%' )->get()->pluck( 'income_benefit_profile_id' )->toArray()
                            )->get()->pluck( 'product_instance_id' )->toArray();
                            break;

                        case 'income_decreasing' :
                            $results = Product::whereIn( 'analysis_data_id',
                                AnalysisGuaranteedCache::where( 'income_low', '!=', \DB::raw( 'analysis_guaranteed_cache.income_initial' ) )->get()->pluck( 'analysis_data_id' )->toArray()
                            )->get()->pluck( 'product_instance_id' )->toArray();
                            break;

                        case 'income_no_reduction' :
                            $results = Product::whereIn( 'analysis_data_id',
                                AnalysisGuaranteedCache::where( 'income_high', '=', \DB::raw( 'analysis_guaranteed_cache.income_low' ) )->get()->pluck( 'analysis_data_id' )->toArray()
                            )->get()->pluck( 'product_instance_id' )->toArray();
                            break;

                        case 'no_rider_fees' :
                            $results = Product::whereNotIn( 'product_instance_id',
                                ProductsInstancesStrategiesFeesCurrent::all()->pluck( 'product_instance_id' )->toArray()
                            )->get()->pluck( 'product_instance_id' )->toArray();
                            break;
                    }
                    break;

                case 'index_age' :
                    if ( intval( $filter_value ) > 0 ) {
                        $target_date = Carbon::now();

                        ProductsInstancesStrategy::whereIn( 'index_id',
                            Index::where( 'oldest_date', '<=', Carbon::now()->subtract( '30', 'years' )->format( 'Y-m-d' ) )->get()->pluck( 'index_id' )->toArray()
                        )->get()->pluck( 'product_instance_id' )->toArray();
                    }
                    break;
            }

            return $results;
        }

        public static function identify_products( $params_strategy = [], $params_rate = [], $annuitant = [], $parameters = [], $inventory = [] ) {
            $counts = [];
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
            $instances = [];

            //if ( ( !empty( $inventory ) ) && ( empty( $parameters[ 'carrier' ] ) ) ) {
            if ( !empty( $inventory ) ) {
                // restrict to saved inventory
                $instances = ProductsInstance::whereIn( 'product_id', $inventory )->get()->pluck( 'product_instance_id' )->toArray();
            } else {
                $instances = ProductsInstance::all()->pluck( 'product_instance_id' )->toArray();
            }

            foreach ( self::$filters as $filter ) {
                if ( !empty( $parameters[ $filter ] ) ) {
                    $filter_results = self::get_products_with_filter( $filter, $parameters[ $filter ] );

                    $counts[ $filter ] = count( $filter_results );

                    // reduce
                    $instances = array_intersect( $instances, $filter_results );
                }
            }

            if ( !empty( $instances ) ) {
                $strategies = $strategies->whereIn( 'product_instance_id', $instances )->get();

                error_log( 'count = ' . $strategies->count() );

                if ( $strategies->count() ) {
                    $matches = ProductsInstancesStrategiesRate::whereIn( 'product_strategy_instance_id', $strategies->pluck( 'instance_id' )->toArray() )
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
            }

            error_log( 'counts = ' . print_r( $counts, true ) );

            return [ 'products' => $products, 'filter_counts' => $counts ];
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
