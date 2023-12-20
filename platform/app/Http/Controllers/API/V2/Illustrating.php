<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Helpers\CANNEXHelper;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ProductHelper;

use App\Models\Product;
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

        /*
        $filename = uniqid() . '.pdf';

        if ( !Storage::exists( 'reports' ) ) {
            Storage::makeDirectory( 'reports' );
        }

        $pdf->save( storage_path( 'app' . DIRECTORY_SEPARATOR . 'reports' . DIRECTORY_SEPARATOR . $filename ) );

        //return response()->json( [ 'error' => false, 'filename' => storage_path( 'app' . DIRECTORY_SEPARATOR . 'reports' . DIRECTORY_SEPARATOR . $filename ) ] );
        */

        return view( 'reporting.illustration' )->with( 'annuitant', $annuitant )->with( 'settings', $settings )->with( 'products', $products_extended )->with( 'illustrations', [ 'guaranteed' => $guaranteed, 'hypothetical' => $hypothetical ] );
    }
}
