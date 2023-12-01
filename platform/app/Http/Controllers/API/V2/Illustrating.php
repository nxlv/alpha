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

    public function generate_report( Request $request ) {
        $response = [];

        $queue = [];

        $products = $request->get( 'products' );
        $settings = $request->get( 'settings' );
        $annuitant = $request->get( 'annuitant' );

        $deferral = $settings[ 'deferral' ];
        $premium = preg_replace( '/[^0-9.]/', '', $settings[ 'premium' ] );

        // generate guaranteed illustration

        // generate hypothetical illustration

        if ( ( !empty( $products ) ) && ( !empty( $annuitant ) ) && ( $premium ) && ( $deferral ) ) {
            foreach ( $products as $product ) {
                $queue[] = CANNEXHelper::build_analysis_request(
                    [
                        'analysis_data_id' => $product,
                        'analysis_cd' => 'B',
                        'index' => ProductHelper::validate_index_dates( $product, time(), $deferral )
                    ],
                    $annuitant,
                    $settings
                );
            }

            $response = CANNEXHelper::analyze_fixed( $queue );
        }

        return view( 'reporting.illustration', $response );
    }
}
