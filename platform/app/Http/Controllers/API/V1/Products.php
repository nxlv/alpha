<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\ProductsProfile;
use App\Models\ProductsInstance;
use App\Models\IncomeBenefit;

class Products extends Controller {
    public function get_all() {
        return response()->json(
            Product::with(
               [
                   'carrier_product',
                   'carrier_product.carrier',
                   'carrier_product.carrier.ratings',
                   'strategy',
                   'strategy.rates',
                   'income_benefit',
                   /*
                   'income_benefit.rider_fee_current',
                   'income_benefit.premium_multiplier',
                   'income_benefit.premium_bonus',
                   'income_benefit.roll_up',
                   'income_benefit.interest_crediting',
                   'income_benefit.interest_bonus_crediting',
                   'income_benefit.interest_multiplier_crediting',
                   'income_benefit.income_start_age'
                   */
               ]
            )->where( 'income_benefit_profile_id', '!=', '' )->get()
        );
    }

    public function get_all_instances() {
        return response()->json(
            ProductsInstance::with(
                'meta',
                'strategies',
                'strategies.fees_current',
                'strategies.fees_maximum',
                'strategies.rates',
                'strategies.rates.substrategies',
                'strategies.rates.substrategies.rates'
            )->get()
        );
    }

    /**
     * Extended product details
     * for fixed annuities
     */
    public function get_product_details( Request $request ) {
        $messages = [];
        $response = [
            'details' => [],
            'options' => []
        ];

        $product_analysis_id = $request->get( 'product', null );

        if ( !empty( $product_analysis_id ) ) {
            try {
                $product = Product::with(
                    [
                        'strategy',
                            'strategy.rates',
                        'carrier_product',
                            'carrier_product.carrier', 'carrier_product.ratings',
                        'death_benefit',
                            'death_benefit.meta',
                            'death_benefit.rider_fee_current', 'death_benefit.rider_fee_minimum', 'death_benefit.rider_fee_maximum',
                            'death_benefit.premium_initial', 'death_benefit.premium_max', 'death_benefit.premium_bonus', 'death_benefit.premium_multiplier',
                            'death_benefit.interest_crediting', 'death_benefit.interest_bonus_crediting', 'death_benefit.interest_multiplier_crediting',
                            'death_benefit.enhancement', 'death_benefit.roll_up', 'death_benefit.step_up', 'death_benefit.states',
                        'income_benefit',
                            'income_benefit.meta',
                            'income_benefit.rider_fee_current', 'income_benefit.rider_fee_minimum', 'income_benefit.rider_fee_maximum',
                            'income_benefit.premium_initial', 'income_benefit.premium_max', 'income_benefit.premium_bonus', 'income_benefit.premium_multiplier',
                            'income_benefit.interest_crediting', 'income_benefit.interest_bonus_crediting', 'income_benefit.interest_multiplier_crediting',
                            'income_benefit.income_start_age',
                            'income_benefit.persistency_credit', 'income_benefit.roll_up', 'income_benefit.step_up', 'income_benefit.states',
                            'income_benefit.withdrawal_tiers', 'income_benefit.withdrawal_tiers_ruin', 'income_benefit.withdrawal_deferral_ages', 'income_benefit.withdrawal_deferral_ages_ruin',
                                'income_benefit.notice_premium_multiplier',
                                'income_benefit.notice_premium_bonus',
                                'income_benefit.notice_premium',
                                'income_benefit.notice_interest_crediting',
                                'income_benefit.notice_interest_multiplier',
                                'income_benefit.notice_issue_age',
                                'income_benefit.notice_step_ups',
                                'income_benefit.notice_persistency_credit',
                                'income_benefit.notice_income_start',
                                'income_benefit.notice_withdrawal_rates',
                                'income_benefit.notice_withdrawal_ruin_rates',
                        'profile',
                            'profile.annuitant_types', 'profile.annuitization_age_max',
                            'profile.cdsc_schedule',
                            'profile.fund_type',
                            'profile.gmv_increase_rate', 'profile.gmv_initial_rate',
                            'profile.initial_premium', 'profile.maximum_premium',
                            'profile.issue_age_annuitant', 'profile.issue_age_owner', 'profile.issue_age_joint_annuitant_rule', 'profile.issue_age_joint_owner_rule',
                            'profile.mva',
                            'profile.ownership_type',
                            'profile.riders_benefit',
                            'profile.rop',
                            'profile.surrender_waiver',
                            'profile.withdrawals_free_rate',
                        'rules',
                            'rules.states'
                    ]
                )->where( 'analysis_data_id', $product_analysis_id )->get();

                if ( $product->count() ) {
                    $response[ 'details' ] = $product;

                    $product_id = $product->first()->product_id;
                    $product_analysis_code = $product->first()->analysis_cd;

                    if ( ( !empty( $product_id ) ) && ( !empty( $product_analysis_code ) ) ) {
                        $options = Product::with(
                            [
                                'strategy', 'strategy.rates',
                                'income_benefit',
                                    'income_benefit.meta',
                                    'income_benefit.rider_fee_current', 'income_benefit.rider_fee_minimum', 'income_benefit.rider_fee_maximum',
                                    'income_benefit.premium_initial', 'income_benefit.premium_max', 'income_benefit.premium_bonus', 'income_benefit.premium_multiplier',
                                    'income_benefit.interest_crediting', 'income_benefit.interest_bonus_crediting', 'income_benefit.interest_multiplier_crediting',
                                    'income_benefit.income_start_age',
                                    'income_benefit.persistency_credit', 'income_benefit.roll_up', 'income_benefit.step_up', 'income_benefit.states',
                                    'income_benefit.withdrawal_tiers', 'income_benefit.withdrawal_tiers_ruin', 'income_benefit.withdrawal_deferral_ages', 'income_benefit.withdrawal_deferral_ages_ruin',
                                'rules',
                                    'rules.states'
                            ]
                        )->where( 'product_id', $product_id )->whereNot( 'analysis_data_id', $product_analysis_id )->where( 'analysis_cd', $product_analysis_code )->get();

                        if ( $options->count() ) {
                            $response[ 'options' ] = $options;
                        }
                    }
                }
            } catch ( \Exception $exception ) {
                return response()->json( [ 'error' => true, 'messages' => print_r( $exception, true ) ] );
            }
        }

        return response()->json( [ 'error' => false, 'messages' => $messages, 'details' => $response[ 'details' ], 'options' => $response[ 'options' ] ] );
    }
}
