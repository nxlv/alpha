<?php

namespace App\Http\Controllers\API\V2;

use App\Models\ProductsInstancesStrategiesRate;
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
        $response = [];
        $products = [];

        $reports = IndexStandardHelper::get_all_reports( 'annuities_reports' );

        if ( !empty( $reports ) ) {
            foreach ( $reports->reports as $report ) {
                foreach ( $report->model_allocation as $model ) {
                    $instance_id = str_pad( $model->vendor_strategy_rate_instance_id, ( strlen( $model->vendor_strategy_rate_instance_id ) + 3 ), '0', STR_PAD_LEFT );

                    array_push( $products, $instance_id );
                }
            }

            $rates = ProductsInstancesStrategiesRate::whereIn( 'instance_id', $products )->get();

            for ( $counter = 0; $counter < count( $reports->reports ); $counter++ ) {
                for ( $counter_inner = 0; $counter_inner < count( $reports->reports[ $counter ]->model_allocation ); $counter_inner++ ) {
                    $reports->reports[ $counter ]->model_allocation[ $counter_inner ]->vendor_strategy_rate_instance_id = str_pad( $reports->reports[ $counter ]->model_allocation[ $counter_inner ]->vendor_strategy_rate_instance_id, ( strlen( $reports->reports[ $counter ]->model_allocation[ $counter_inner ]->vendor_strategy_rate_instance_id ) + 3 ), '0', STR_PAD_LEFT );

                    foreach ( $rates as $rate ) {
                        if ( $rate->instance_id === $reports->reports[ $counter ]->model_allocation[ $counter_inner ]->vendor_strategy_rate_instance_id ) {
                            $reports->reports[ $counter ]->model_allocation[ $counter_inner ]->vendor_strategy_rate_instance = $rate;
                            break;
                        }
                    }
                }
            }
        }

        return response()->json( [ 'error' => empty( $reports ), 'result' => $reports ] );
    }
}
