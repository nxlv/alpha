<?php

namespace App\Http\Helpers;

use Phpml\Regression\LeastSquares;

class HeuristicHelper {
    public static function predict( $dataset, $method, $premium, $income, $deferrals, $debug = false ) {
        $response = [];

        $known_params = [];
        $known_income = [];

        foreach ( $dataset as $row ) {
            switch ( $method ) {
                case 'premium' :
                    $known_params[] = [ $row->premium, $row->deferral ];
                    $known_income[] = ( ( isset( $row->income_low ) ) ? $row->income_low : $row->income );
                    break;

                case 'income' :
                    $known_params[] = [ ( isset( $row->income_low ) ) ? $row->income_low : $row->income, $row->deferral ];
                    $known_income[] = $row->premium;
                    break;
            }
        }

        error_log( print_r( $known_income, true ) );
        error_log( print_r( $known_params, true ) );

        // Use linear regression to fit a model to the data
        $regression = new LeastSquares();
        $regression->train( $known_params, $known_income );

        if ( !is_array( $deferrals ) ) {
            $deferrals = [ $deferrals ];
        }

        foreach ( $deferrals as $deferral ) {
            error_log( 'deferral = ' . $deferral . ' | income = ' . $income . ' | premium = ' . $premium );

            switch ( $method ) {
                case 'income' :
                    $response[] = [
                        'deferral' => $deferral,
                        'premium' => $regression->predict( [ $income, $deferral ] ),
                        'income' => $income
                    ];
                    break;

                case 'premium' :
                    $response[] = [
                        'deferral' => $deferral,
                        'premium' => $premium,
                        'income' => $regression->predict( [ $premium, $deferral ] )
                    ];
                    break;
            }
        }

        return $response;
    }
}
