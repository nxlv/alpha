<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;

use App\Http\Helpers\WSSoapClient;
use App\Http\Helpers\ProductHelper;
use App\Http\Helpers\CANNEXHelper;
use App\Http\Helpers\HeuristicHelper;

use Carbon\Carbon;

use App\Models\AnalysisCache;
use App\Models\AnalysisGuaranteedCache;
use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Quoting extends Controller {
    /**
     * SPIA/DIA Quoting
     * for immediate annuities
     */
    public function query_spia_dia( Request $request ) {
        $messages = [];

        $endpoint_url = env( 'CANNEX_WS_ENDPOINT_IMMEDIATE' );
        $username = env( 'CANNEX_WS_USERNAME' );
        $password = env( 'CANNEX_WS_PASSWORD' );
        $token_type = env( 'CANNEX_WS_DIGEST_TYPE' );

        try {
            $client = new WSSoapClient( storage_path( 'app/public/wsdl/quoting/canx_antu-2.0.wsdl' ), [ 'trace' => 0, 'exception' => 0 ] );
            $client->__setLocation( $endpoint_url );
            $client->__setUsernameToken( $username, $password, $token_type );
        } catch ( \SoapFault $exception ) {
            return response()->json( [ 'error' => true, 'messages' => print_r( $exception, true ) ] );
        }

        $result = null;

        $parameters = [
            'method' => $request->get( 'method', 'income' ),
            'deferral' => $request->get( 'deferral', 0 ),
            'premium' => $request->get( 'premium', '0.00' ),
            'income' => $request->get( 'income', '0.00' ),
            'fund_type' => $request->get( 'fund_type', 'N' ),
            'return_of_premium' => $request->get( 'return_of_premium', 'GT' ),
            'guarantee' => $request->get( 'guarantee', 'RG' ),
            'index_type' => $request->get( 'index_type', 'N' ),
            'joint_type' => $request->get( 'joint_type', 'N' ),
            'frequency' => $request->get( 'frequency', 'M' ),
            'survey_type' => $request->get( 'survey_type', 'SL' ),
            'owner' => [
                'name' => $request->get( 'owner_name', '' ),
                'dob' => $request->get( 'owner_dob', '1949-08-07' ),
                'gender' => $request->get( 'owner_gender', 'M' ),
                'region' => $request->get( 'region', 'FL' ),
                'is_primary' => $request->get( 'owner_primary', 'Y' ),
            ],
            'joint' => [
                'name' => $request->get( 'joint_name', null ),
                'dob' => $request->get( 'joint_dob', null ),
                'gender' => $request->get( 'joint_gender', null ),
                'is_spouse' => $request->get( 'joint_spouse', null )
            ]
        ];

        $arguments = array(
            'canx_antu_operation' => array(
                'logon_id' => $username,
                'user_id' => null,
                'app' => 'CANX',
                'birth_date' => $parameters[ 'owner' ][ 'dob' ],
                'joint_birth_date' => $parameters[ 'joint' ][ 'dob' ],
                'owner_birth_date' => $parameters[ 'owner' ][ 'dob' ],
                'premium_purchase_date' => gmdate( 'Y-m-d' ),
                'first_payment_date' => gmdate( 'Y-m-d', strtotime( gmdate( 'Y-m-d' ) ) + ( 86400 * 30 ) ),
                'fund_type_cd' => $parameters[ 'fund_type' ],
                'return_of_premium_cd' => $parameters[ 'return_of_premium' ],
                'guarantee_cd' => $parameters[ 'guarantee' ],
                'indicate_impaired' => null,
                'index_rate' => null,
                'index_type_cd' => $parameters[ 'index_type' ],
                'joint_type_cd' => null,
                'name' => $parameters[ 'owner' ][ 'name' ],
                'owner' => $parameters[ 'owner' ][ 'name' ],
                'joint_name' => $parameters[ 'joint' ][ 'name' ],
                'is_owner_primary' => $parameters[ 'owner' ][ 'is_primary' ],
                'payment_frequency_cd' => $parameters[ 'frequency' ],
                'percent' => null,
                'joint_percent' => null,
                'prepared_by' => env( 'APP_NAME', 'ALPHA' ),
                'region_cd' => $parameters[ 'owner' ][ 'region' ],
                'region_issued_cd' => $parameters[ 'owner' ][ 'region' ],
                'request_description' => 'Fred Client Quote',
                'gender_cd' => $parameters[ 'owner' ][ 'gender' ],
                'owner_gender_cd' => $parameters[ 'owner' ][ 'gender' ],
                'joint_gender_cd' => $parameters[ 'joint' ][ 'gender' ],
                'is_spouse' => $parameters[ 'joint' ][ 'is_spouse' ],
                'survey_type_cd' => $parameters[ 'survey_type' ],
                'parameter_set_cd' => 'L',
                'institution_id' => null,
                'filter_customer' => null,
                'filter_institution_id' => null,
                'trace_level_cd' => 0,
                'return_of_premium_rate' => 0.0,
                'ratings_company_cd' => null,
                'ratings_cd' => null,
                'exclude_not_rated' => 'Y',
                'is_test' => null
            )
        );

        // NOTE: should we add these parameters as UI controls?
        $arguments[ 'canx_antu_operation' ][ 'guarantee_year' ] = intval( $parameters[ 'deferral' ] );
        $arguments[ 'canx_antu_operation' ][ 'guarantee_month' ] = 0;

        switch ( $parameters[ 'method' ] ) {
            case 'income' :
                // solve for required premium for desired monthly income
                $arguments[ 'canx_antu_operation' ][ 'cost_basis' ] = null;
                $arguments[ 'canx_antu_operation' ][ 'premium' ] = null;
                $arguments[ 'canx_antu_operation' ][ 'income' ] = preg_replace( '/[^\d.]|(?<!\d)\./', '', $parameters[ 'income' ] );
                break;

            case 'premium' :
                // solve to determine income based on desired premium
                $arguments[ 'canx_antu_operation' ][ 'cost_basis' ] = preg_replace( '/[^\d.]|(?<!\d)\./', '', $parameters[ 'premium' ] );
                $arguments[ 'canx_antu_operation' ][ 'premium' ] = $arguments[ 'canx_antu_operation' ][ 'cost_basis' ];
                $arguments[ 'canx_antu_operation' ][ 'income' ] = 0.0;
                break;
        }

        if ( ( !empty( $parameters[ 'joint' ][ 'name' ] ) ) && ( !empty( $parameters[ 'joint' ][ 'dob' ] ) ) && ( !empty( $parameters[ 'joint' ][ 'gender' ] ) ) ) {
            // set to joint life survey type
            $arguments[ 'canx_antu_operation' ][ 'survey_type_cd' ] = 'JL';
            $arguments[ 'canx_antu_operation' ][ 'joint_percent' ] = 100.00;
            $arguments[ 'canx_antu_operation' ][ 'joint_type_cd' ] = $parameters[ 'joint_type' ];
        }

        try {
            $result = $client->__call( 'canx_antu_operation', $arguments );

            if ( !empty( $result ) ) {
                if ( isset( $result->survey_notes ) ) {
                    foreach ( $result->survey_notes as $note ) {
                        array_push( $messages, $note );
                    }
                }

                if ( isset( $result->carrier ) ) {
                    $result = $result->carrier;
                }
            }
        } catch ( \SoapFault $exception ) {
            // error
            array_push( $messages, 'An error occurred while querying CANNEX.  The message was: ' . print_r( $exception, true ) );
        }

        return response()->json( [ 'result' => $result, 'messages' => $messages ] );
    }
}
