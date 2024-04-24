<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Helpers\CANNEXHelper;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ProductHelper;

use App\Models\Product;
use App\Models\Index;
use App\Models\Notice;

use App\Models\AnalysisGuaranteedCache;
use Illuminate\Support\Facades\Storage;

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
                    'strategy',
                        'strategy.rates',
                    'carrier_product',
                        'carrier_product.carrier', 'carrier_product.ratings',
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
            )->get();

        /**
         *
         * Phase 2:
         * Generate /guaranteed/ income
         *
         */
        $queue = [];

        foreach ( $products as $product ) {
            $queue[] = CANNEXHelper::build_evaluate_request(
                [
                    'analysis_data_id' => $product,
                    'index' => ProductHelper::validate_index_dates( $product, date( 'Y-m-d' ), $deferral )       // time() here assumes purchase date of today.  TODO: allow overriding this
                ],
                $annuitant,
                $settings
            );
        }

        $guaranteed = CANNEXHelper::analyze_fixed_zero_return( $queue, $settings );

        /**
         *
         * Phase 3:
         * Generate /hypothetical/ income and illustration
         *
         */
        $queue = [];

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

        /*
        $pdf = Pdf::loadView(
            'reporting.illustration',
            [
                'annuitant' => $annuitant,
                'settings' => $settings,
                'products' => $products_extended,
                'illustrations' => [
                    'guaranteed' => $guaranteed,
                    'hypothetical' => $hypothetical
                ]
            ]
        );
        */

        /*
        $filename = uniqid() . '.pdf';

        if ( !Storage::exists( 'reports' ) ) {
            Storage::makeDirectory( 'reports' );
        }

        $pdf->save( storage_path( 'app' . DIRECTORY_SEPARATOR . 'reports' . DIRECTORY_SEPARATOR . $filename ) );

        //return response()->json( [ 'error' => false, 'filename' => storage_path( 'app' . DIRECTORY_SEPARATOR . 'reports' . DIRECTORY_SEPARATOR . $filename ) ] );
        */

        $dataset = [
            'annuitant' => $annuitant,
            'settings' => $settings,
            'products' => $products_extended,
            'datasets' => [
                'indexes' => Index::all(),
                'notices' => Notice::all()
            ],
            'illustrations' => [
                'guaranteed' => $guaranteed,
                'hypothetical' => $hypothetical
            ]
        ];

        return view( 'reporting.illustration', $dataset );
    }
}
