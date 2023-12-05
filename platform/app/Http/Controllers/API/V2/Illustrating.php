<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Helpers\CANNEXHelper;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ProductHelper;

use App\Models\Product;
use App\Models\AnalysisGuaranteedCache;

class Illustrating extends Controller {
    public static $indexes = [];

    public function fetch_report( Request $request ) {
        $response = [];

        $queue = [];

        $products = $request->get( 'products' );
        $settings = $request->get( 'settings' );
        $annuitant = $request->get( 'annuitant' );

        if ( !is_array( $products ) ) {
            $products = [ $products ];
        }

        $hypothetical = [];
        $guaranteed = [];

        $deferral = $settings[ 'deferral' ];
        $premium = preg_replace( '/[^0-9.]/', '', $settings[ 'premium' ] );
        $transaction_id = uuid_create();

        /**
         *
         * Phase 1:
         * Fetch basic product information
         *
         */
        $products_extended = Product::whereIn( 'analysis_data_id', $products )
            ->with(
                [
                    'carrier_product',
                    'carrier_product.carrier',
                    'carrier_product.carrier.ratings',
                    'strategy',
                    'strategy.rates',
                    'income_benefit',
                    'income_benefit.rider_fee_current',
                    'income_benefit.income_start_age',
                    'income_benefit.premium_multiplier',
                    'income_benefit.premium_bonus',
                    'income_benefit.roll_up',
                    'income_benefit.step_up',
                ]
            )->get();

        /**
         *
         * Phase 2:
         * Generate /guaranteed/ income
         *
         */
        $parameters_guaranteed = [
            'state_cd' => $annuitant[ 'owner_state' ],
            'contract_cd' => $annuitant[ 'annuity_type' ],
            'premium' => $premium,
            'purchase_date' => date( 'Y-m-d' ),
            'gender_cd_primary' => $annuitant[ 'owner_gender' ],
            'purchase_age_primary' => $annuitant[ 'owner_age' ],
            'income_start_age_primary' => ( intval( $annuitant[ 'owner_age' ] ) + intval( $settings[ 'deferral' ] ) )
        ];

        if ( $annuitant[ 'annuity_type' ] === 'J' ) {
            $parameters_guaranteed[ 'gender_cd_joint' ] = 'F';
            $parameters_guaranteed[ 'purchase_age_joint' ] = date( 'Y-m-d' );
            $parameters_guaranteed[ 'income_start_age_joint' ] = ( intval( $annuitant[ 'joint_age' ] ) + intval( $settings[ 'deferral' ] ) );
        }

        if ( $profile_id = CANNEXHelper::create_annuitant_profile( $transaction_id, $parameters_guaranteed, 0, $products ) ) {
            $results = CANNEXHelper::get_guaranteed_rates( $profile_id, $transaction_id );

            if ( ( isset( $results->income_request_data ) ) && ( isset( $results->income_response_data ) ) ) {
                if ( is_array( $results->income_response_data ) ) {
                    foreach ( $results->income_response_data as $result ) {
                        $guaranteed[] = $result;
                    }
                }
            }
        }

        /**
         *
         * Phase 2:
         * Generate /hypothetical/ income and illustration
         *
         */
        if ( ( !empty( $products ) ) && ( !empty( $annuitant ) ) && ( $premium ) && ( $deferral ) ) {
            foreach ( $products as $product ) {
                $queue[] = CANNEXHelper::build_analysis_request(
                    [
                        'analysis_data_id' => $product,
                        'analysis_cd' => 'B',
                        'index' => ProductHelper::validate_index_dates( $product, date( 'Y-m-d' ), $deferral )       // time() here assumes purchase date of today.  TODO: allow overriding this
                    ],
                    $annuitant,
                    $settings
                );
            }

            $hypothetical = CANNEXHelper::analyze_fixed( $queue );
        }

        return view( 'reporting.illustration' )->with( 'annuitant', $annuitant )->with( 'products', $products_extended )->with( 'guaranteed', $guaranteed )->with( 'hypothetical', $hypothetical );
    }
}
