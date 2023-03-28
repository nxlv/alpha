<?php

namespace App\Http\Helpers;

use Phpml\Regression\LeastSquares;

class HeuristicHelper {
    public static function predict( $dataset, $premium, $deferrals ) {
        $response = [];

        $known_params = [];
        $known_income = [];

        foreach ( $dataset as $row ) {
            $known_params[] = [ $row->premium, $row->deferral ];
            $known_income[] = $row->income;
        }

        // Use linear regression to fit a model to the data
        $regression = new LeastSquares();
        $regression->train( $known_params, $known_income );

        if ( !is_array( $deferrals ) ) {
            $deferrals = [ $deferrals ];
        }

        foreach ( $deferrals as $deferral ) {
            $response[] = [
                'deferral' => $deferral,
                'premium' => $premium,
                'income' => $regression->predict( [ $premium, $deferral ] )
            ];
        }

        return $response;
    }
}
