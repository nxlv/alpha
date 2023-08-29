<?php

    namespace App\Http\Helpers;

    use App\Http\Helpers\WSSoapClient;

    class CANNEXHelper {
        //const ANTY_ANLY_VERSION_ID = 'BY13MD';
        const ANTY_ANLY_VERSION_ID = 'C4FB2W';
        const MAX_POLL_RETRIES = 25;

        public static function analyze_fixed( $products ) {
            $result = [];

            $endpoint_url = env( 'CANNEX_WS_ENDPOINT_FIXED' );
            $username = env( 'CANNEX_WS_USERNAME' );
            $password = env( 'CANNEX_WS_PASSWORD' );
            $token_type = env( 'CANNEX_WS_DIGEST_TYPE' );
            $endpoint_function = 'canx_anty_anly_operation';

            try {
                $client = new WSSoapClient( storage_path( 'app/public/wsdl/quoting/canx_anty_anly-1.0.wsdl' ), [
                    'trace'         => 0,
                    'cache_wsdl'    => WSDL_CACHE_NONE,
                    'exception'     => 0
                ] );
                $client->__setLocation( $endpoint_url );
                $client->__setUsernameToken( $username, $password, $token_type );
            } catch ( \SoapFault $exception ) {
                return false;
            }

            $query = array(
                $endpoint_function => array(
                    'logon_id'         => $username,
                    'user_id'          => null,
                    'transaction_id'   => null,
                    'analysis_request' => $products
                )
            );

            try {
                $response = $client->__call( $endpoint_function, $query );

                if ( property_exists( $response, 'analysis_response' ) ) {
                    if ( !is_array( $response->analysis_response ) ) {
                        $result[] = $response->analysis_response;
                    } else {
                        $result = $response->analysis_response;
                    }
                }
            } catch ( \SoapFault $exception ) {
                // error
                // TODO: Log it
            }

            return $result;
        }

        public static function build_analysis_request( $product_analysis_id, $arguments ) {
            $parameters = array_merge(
                [
                    'method' => 'premium',
                    'deferral' => 10,
                    'premium' => 0.00,
                    'income' => 0.00,
                    'frequency' => 'A',
                    'analysis_cd' => 'B',
                    'horizon' => 1,

                    'owner_name' => 'ALPHA',
                    'owner_dob' => gmdate( 'Y-m-d', gmmktime( 0, 0, 0, 1, 1, gmdate( 'Y' ) - 55 ) ),
                    'owner_gender' => 'M',
                    'owner_region', 'FL',
                    'owner_is_primary' => 'Y',

                    'joint_name' => null,
                    'joint_dob' => null,
                    'joint_gender' => null,
                    'joint_is_spouse' => null

                    //'index_range' => [],
                    //'set_index_range' => true
                ],
                $arguments
            );

            /*
            if ( $parameters[ 'set_index_range' ] ) {
                $parameters[ 'index_range' ] = [
                    'start_month' => gmdate( 'n' ),
                    'start_year' => ( gmdate( 'Y' ) - ( 2 + intval( $parameters[ 'deferral' ] ) ) ),
                    'end_month' => 12,
                    'end_year' => ( gmdate( 'Y' ) - 2 )
                ];
            }
            */

            $response = [
                'contract_cd'                 => 'S',
                'premium'                     => $parameters[ 'premium' ],
                'purchase_date'               => gmdate( 'Y-m-d\TH:i:s.v\Z' ),
                'gender_cd_primary'           => $parameters[ 'owner_gender' ],
                'gender_cd_joint'             => $parameters[ 'joint_gender' ],
                'purchase_age_primary'        => ( gmdate( 'Y' ) - gmdate( 'Y', strtotime( $parameters[ 'owner_dob' ] ) ) ),
                'purchase_age_joint'          => null,
                'income_start_age_primary'    => ( intval( ( gmdate( 'Y' ) - gmdate( 'Y', strtotime( $parameters[ 'owner_dob' ] ) ) ) ) + intval( $parameters[ 'deferral' ] ) ),
                'income_start_age_joint'      => null,
                'index_time_range'            => [
                    'start_month' => gmdate( 'n' ),
                    'start_year' => ( gmdate( 'Y' ) - ( 2 + intval( $parameters[ 'deferral' ] ) ) ),
                    'end_month' => 12,
                    'end_year' => ( gmdate( 'Y' ) - 2 )
                ],
                'anty_ds_version_id'          => self::ANTY_ANLY_VERSION_ID,
                'analysis_cd'                 => $parameters[ 'analysis_cd' ],
                'analysis_data_id'            => $product_analysis_id,
                'analysis_time_horizon_years' => ( intval( $parameters[ 'deferral' ] ) + intval( $parameters[ 'horizon' ] ) ),
                'is_test'                     => 'N'
            ];

            return $response;
        }

        public static function create_annuitant_profile( $transaction_id, $parameters, $dataset ) {
            $request_id = null;

            $endpoint_url = env( 'CANNEX_WS_ENDPOINT_INCOME' );
            $username = env( 'CANNEX_WS_USERNAME' );
            $password = env( 'CANNEX_WS_PASSWORD' );
            $token_type = env( 'CANNEX_WS_DIGEST_TYPE' );
            $function_name = 'canx_anty_inc1_operation';

            try {
                $client = new WSSoapClient( storage_path( 'app/public/wsdl/quoting/canx_anty_inc1-1.0.wsdl' ), [
                    'trace'         => 0,
                    'cache_wsdl'    => WSDL_CACHE_NONE,
                    'exception'     => 0
                ] );
                $client->__setLocation( $endpoint_url );
                $client->__setUsernameToken( $username, $password, $token_type );

                $arguments = array(
                    'canx_anty_inc1_operation' => array(
                        'income_request1_set' => array(
                            'logon_id' => $username,
                            'user_id' => '',
                            'transaction_id' => $transaction_id,
                            'anty_ds_version_id' => self::ANTY_ANLY_VERSION_ID,
                            'analysis_data_id' => $dataset,
                            'cnx_sequence_id' => [ 0 ], // [ 0, 1 ]
                            'income_request_data' => $parameters,
                            'is_test' => 'N'
                        )
                    )
                );

                try {
                    $result = $client->__call( $function_name, $arguments );

                    if ( ( isset( $result->income_response1 ) ) && ( $result->income_response1->income_request_id ) ) {
                        $request_id = $result->income_response1->income_request_id;
                    }
                } catch ( SoapFault $exception ) {
                }
            } catch ( \SoapFault $exception ) {
                return false;
            }

            return $request_id;
        }

        public static function get_guaranteed_rates( $profile_id, $transaction_id ) {
            $result = [];

            $endpoint_url = env( 'CANNEX_WS_ENDPOINT_INCOME' );
            $username = env( 'CANNEX_WS_USERNAME' );
            $password = env( 'CANNEX_WS_PASSWORD' );
            $token_type = env( 'CANNEX_WS_DIGEST_TYPE' );
            $function_name = 'canx_anty_inc1_operation';

            try {
                $client = new WSSoapClient( storage_path( 'app/public/wsdl/quoting/canx_anty_inc1-1.0.wsdl' ), [
                    'trace'         => 0,
                    'cache_wsdl'    => WSDL_CACHE_NONE,
                    'exception'     => 0
                ] );
                $client->__setLocation( $endpoint_url );
                $client->__setUsernameToken( $username, $password, $token_type );

                $arguments = array(
                    'canx_anty_inc1_operation' => array(
                        'income_request1' => array(
                            'logon_id' => $username,
                            'user_id' => '',
                            'transaction_id' => $transaction_id,
                            'income_request_id' => $profile_id,
                            'is_test' => 'N'
                        )
                    )
                );

                $retry_count = 0;

                while ( true ) {
                    try {
                        $analysis = $client->__call( $function_name, $arguments );

                        echo sprintf( '[+] Request status: %s', $analysis->income_response1_set->status_cd ) . PHP_EOL;

                        if ( $analysis->income_response1_set->status_cd === 'P' ) {
                            echo '[+] Request pending, polling in 3 seconds...' . PHP_EOL;

                            sleep(3);
                        } else {
                            $result = $analysis->income_response1_set;
                            break;
                        }
                    } catch ( SoapFault $exception ) {
                        print_r( $exception );
                    }

                    $retry_count++;

                    if ( $retry_count >= self::MAX_POLL_RETRIES ) {
                        break;
                    }
                }
            } catch ( SoapFault $exception ) {
                print_r( $exception );
            }

            return $result;
        }
    }
