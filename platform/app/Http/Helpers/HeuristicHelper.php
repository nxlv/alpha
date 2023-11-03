<?php

namespace App\Http\Helpers;

use App\Models\AnalysisGuaranteedCache;
use Phpml\Regression\LeastSquares;

class HeuristicHelper {
    public static function predict_guaranteed( $cache, $analysis_data_id, $annuitant, $method, $amount, $deferrals ) {
        $response = [];

        if ( $cache->count() ) {
            $breakpoints = [];

            $known_params = [];
            $known_income = [];

            foreach ( $cache as $row ) {
                if ( $row->analysis_data_id === $analysis_data_id ) {
                    $known_params[] = [ $row->purchase_age, $row->deferral ];
                    $known_income[] = $row->income_initial;

                    $breakpoints[] = [
                        'purchase_age' => $row->purchase_age,
                        'deferral' => $row->deferral,
                        'amount' => $row->premium,
                        'income' => $row->income_initial
                    ];
                }
            }

            if ( ( !empty( $known_params ) ) && ( !empty( $known_income ) ) && ( count( $known_params ) == count( $known_income ) ) ) {
                error_log( 'STARTING PREDICTIONS' );

                error_log( print_r( $known_params, true ) );
                error_log( print_r( $known_income, true ) );


                $regression = new LeastSquares();
                $regression->train( $known_params, $known_income );

                foreach ( $deferrals as $deferral ) {
                    $income = 0;

                    foreach ( $breakpoints as $breakpoint ) {
                        error_log( 'BREAKPOINT CHECK: D: ' . $deferral . ' vs. ' . $breakpoint[ 'deferral' ] . ' | A: ' . $annuitant[ 'owner_age' ] . ' vs. ' . $breakpoint[ 'purchase_age' ] );

                        if ( ( $breakpoint[ 'deferral' ] === $deferral ) && ( intval( $breakpoint[ 'purchase_age' ] ) === intval( $annuitant[ 'owner_age' ] ) ) ) {
                            error_log( '------------------------------------' );
                            error_log( 'BREAKPOINT FOUND: D: ' . $deferral . ' vs. ' . $breakpoint[ 'deferral' ] . ' | A: ' . $annuitant[ 'owner_age' ] . ' vs. ' . $breakpoint[ 'purchase_age' ] );

                            $income = $breakpoint[ 'income' ];
                            break;
                        } else {
                            $income = $regression->predict( [ $annuitant[ 'owner_age' ], $deferral ] );
                        }
                    }

                    $response[] = [
                        'purchase_age' => $annuitant[ 'owner_age' ],
                        'deferral' => $deferral,
                        'amount' => $income * ( $amount / 100 )
                    ];
                }

                error_log( 'ENDING PREDICTIONS' );
            }
        }

        return $response;
    }

    /* TODO: Refactor to be like predict_guaranteed */
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
