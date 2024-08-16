<?php

namespace App\Http\Controllers\API\V2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

use App\Http\Helpers\IndexStandardHelper;

use App\Models\Index;

class Indexes extends Controller {
    public static function get_reports() {
        $response = [];
        $indexes = IndexStandardHelper::get_all_reports( 'reports' );
        $reports = IndexStandardHelper::get_all_reports( 'annuities_reports' );

        if ( ( !empty( $indexes ) ) && ( !empty( $reports ) ) ) {
            foreach ( $indexes as $index ) {
                $element = $index;

                $product_count = 0;

                foreach ( $reports->reports as $report ) {
                    foreach ( $report->model_allocation as $model ) {
                        if ( ( isset( $model->index_ticker ) ) && ( ( $model->index_ticker === $index->ticker ) || ( $model->index_ticker === $index->eod_ticker ) ) ) {
                            $product_count++;
                        }
                    }
                }

                $element->product_count = $product_count;

                array_push( $response, $element );
            }
        }

        return response()->json( [ 'error' => empty( $response ), 'result' => $response ] );
    }

    public static function get_annuities() {
        $response = IndexStandardHelper::get_all_reports( 'annuities_reports' );

        return response()->json( [ 'error' => empty( $response ), 'result' => $response ] );
    }
}
