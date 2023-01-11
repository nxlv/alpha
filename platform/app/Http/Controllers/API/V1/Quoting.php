<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\WSSoapClient;

class Quoting extends Controller {
    public function query() {
        $endpoint_url = env( 'CANNEX_QUOTING_ENDPOINT' );
        $username = env( 'CANNEX_QUOTING_USERNAME' );
        $password = env( 'CANNEX_QUOTING_PASSWORD' );
        $token_type = env( 'CANNEX_QUOTING_DIGEST_TYPE' );

        try {
            $client = new WSSoapClient( storage_path( 'app/public/wsdl/quoting/canx_antu-2.0.wsdl' ), [ 'trace' => 0, 'exception' => 0 ] );
            $client->__setLocation( $endpoint_url );
            $client->__setUsernameToken( $username, $password, $token_type );
        } catch ( \SoapFault $exception ) {
            return response()->json( [ 'error' => true, 'messages' => print_r( $exception, true ) ] );
        }

        $request = null;
        $result = null;
        $messages = [];

        $parameters = [
            'deferral' => 10,
            'mode' => true,
            'investment' => 100000.00,
            'monthly' => 5000.00
        ];

        $arguments = array(
            'canx_antu_operation' => array(
                'logon_id' => $username,
                'user_id' => null,
                'app' => 'CANX',
                'birth_date' => '1949-08-07',
                'joint_birth_date' => '1947-10-15',
                'owner_birth_date' => '1949-08-07',
                'premium_purchase_date' => gmdate( 'Y-m-d' ),
                'fund_type_cd' => 'N',
                'return_of_premium_cd' => 'GT',
                'guarantee_cd' => 'RG',
                'guarantee_year' => 10,
                'guarantee_month' => 0,
                'indicate_impaired' => null,
                'index_rate' => null,
                'index_type_cd' => 'N',
                'joint_type_cd' => 'N',
                'name' => 'Fred Client',
                'joint_name' => 'Amy Client',
                'owner' => 'Mr. Fred Client',
                'is_owner_primary' => 'Y',
                'payment_frequency_cd' => 'M',
                'percent' => 100.000,
                'joint_percent' => 100.000,
                'prepared_by' => 'Mr. Agent',
                'region_cd' => 'FL',
                'region_issued_cd' => 'FL',
                'request_description' => 'Fred Client Quote',
                'gender_cd' => 'M',
                'joint_gender_cd' => 'F',
                'owner_gender_cd' => 'M',
                'is_spouse' => 'Y',
                'survey_type_cd' => 'JL',
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

        $arguments[ 'canx_antu_operation' ][ 'guarantee_year' ] = intval( $parameters[ 'deferral' ] );
        $arguments[ 'canx_antu_operation' ][ 'first_payment_date' ] = gmdate( 'Y-m-d', strtotime( gmdate( 'Y-m-d' ) ) + ( 86400 * 30 ) );

        if ( isset( $parameters[ 'mode' ] ) ) {
            $arguments[ 'canx_antu_operation' ][ 'cost_basis' ] = preg_replace( '/[^\d.]|(?<!\d)\./', '', $parameters[ 'investment' ] );
            $arguments[ 'canx_antu_operation' ][ 'premium' ] = $arguments[ 'canx_antu_operation' ][ 'cost_basis' ];
            $arguments[ 'canx_antu_operation' ][ 'income' ] = 0.0;
        } else {
            $arguments[ 'canx_antu_operation' ][ 'cost_basis' ] = null;
            $arguments[ 'canx_antu_operation' ][ 'premium' ] = null;
            $arguments[ 'canx_antu_operation' ][ 'income' ] = preg_replace( '/[^\d.]|(?<!\d)\./', '', $parameters[ 'monthly' ] );
        }

        $request = $arguments;

        try {
            $result = $client->__call( 'canx_antu_operation', $arguments );

            if ( !empty( $result ) ) {
                if ( isset( $result->survey_notes ) ) {
                    foreach ( $result->survey_notes as $note ) {
                        array_push( $messages, $note->_ );
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

    /*
    return_of_premium_cd
    Return of Premium before Income Start Date
    "GT" = No
    "RP" = Yes

    payment_frequency_cd
    "M" = Monthly
    "Q" = Quarterly
    "SA" = Semi-Annually
    "A" = Annually


     */
}
