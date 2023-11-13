<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Helpers\CANNEXHelper;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ProductHelper;

use App\Models\Product;
use App\Models\AnalysisGuaranteedCache;

class Quoting extends Controller {
    const YEARS_DEFERRAL_MIN = 5;
    const YEARS_DEFERRAL_MAX = 25;
    const PREMIUM_FAILSAFE = 10000;
    const INCOME_FAILSAFE = 100;

    public function query_fixed( Request $request ) {
        $messages = [];

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

        if ( $profile_id = CANNEXHelper::create_annuitant_profile( $transaction_id, $parameters, 0, $products ) ) {
            $results = CANNEXHelper::get_guaranteed_rates( $profile_id, $transaction_id );

            if ( ( isset( $results->income_request_data ) ) && ( isset( $results->income_response_data ) ) ) {
                if ( !is_array( $results->income_response_data ) ) {

                } else {
                    foreach ( $results->income_response_data as $result ) {
                        $response[] = $result;
                    }
                }
            }
        }

        return $response;
    }
}
