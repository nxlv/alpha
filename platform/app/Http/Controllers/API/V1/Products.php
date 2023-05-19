<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\ProductsProfile;
use App\Models\ProductsInstance;

class Products extends Controller {
    public function get_all() {
        return response()->json(
            ProductsProfile::with(
                'meta',
                'basic',
                'basic.carrier',
                'annuitant_types',
                'annuitization_age_max',
                'cdsc_schedule',
                'fund_type',
                'gmv_increase_rate',
                'gmv_initial_rate',
                'initial_premium',
                'issue_age_annuitant',
                'issue_age_owner',
                'issue_age_joint_annuitant_rule',
                'issue_age_joint_owner_rule',
                'maximum_premium',
                'mva',
                'ownership_type',
                'riders_benefit',
                'rop',
                'states',
                'surrender_waiver',
                'withdrawals_free_rate'
                /*
                'instances',
                'instances.meta',
                'instances.strategies',
                'instances.strategies.index',
                'instances.strategies.fees_current',
                'instances.strategies.fees_maximum',
                'instances.strategies.rates',
                'instances.strategies.rates.substrategies',
                'instances.strategies.rates.substrategies.index',
                'instances.strategies.rates.substrategies.rates'
                */
            )->get()
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
        $response = [];

        $products = $request->get( 'products', [] );

        if ( !empty( $products ) ) {
            if ( !is_array( $products ) ) {
                $products = [ $products ];
            }

            try {
                $strategies = Product::with(
                    [
                        'strategy',
                        'carrier_product', 'carrier_product.carrier', 'carrier_product.ratings',
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
                            'income_benefit.withdrawal_tiers', 'income_benefit.withdrawal_tiers_ruin', 'income_benefit.withdrawal_deferral_ages', 'income_benefit.withdrawal_deferral_ages_ruin'
                    ]
                )->whereIn( 'analysis_data_id', $products )->get();

                if ( $strategies->count() ) {
                    $response = $strategies;
                }
            } catch ( \SoapFault $exception ) {
                return response()->json( [ 'error' => true, 'messages' => print_r( $exception, true ) ] );
            }
        }

        return response()->json( [ 'error' => false, 'messages' => $messages, 'details' => $response ] );
    }
}
