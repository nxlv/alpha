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
    public function generate_report( Request $request ) {
        $data = [];

        $annuitant = $request->get( 'annuitant' );
        $parameters = $request->get( 'parameters' );

        // generate guaranteed illustration

        // generate hypothetical illustration
        $illustration = CANNEXHelper::build_analysis_request()

        return view( 'reporting.illustration', $data );
    }
}
