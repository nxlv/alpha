<?php

namespace App\Http\Controllers\API\V2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

use App\Http\Helpers\IndexStandardHelper;

use App\Models\Index;

class Indexes extends Controller {
    public static function get_reports() {
        $response = IndexStandardHelper::get_all_reports( 'reports' );

        return response()->json( [ 'error' => empty( $response ), 'result' => $response ] );
    }

    public static function get_annuities() {
        $response = IndexStandardHelper::get_all_reports( 'annuities_reports' );

        return response()->json( [ 'error' => empty( $response ), 'result' => $response ] );
    }
}
