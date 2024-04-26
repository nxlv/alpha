<?php

    namespace App\Http\Helpers;

    use App\Models\ProductsInstancesStrategy;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Config;

    use App\Http\Helpers\WSSoapClient;

    class CANNEXHelper {
        const ANTY_ANLY_VERSION_ID = 'CD6D3M';
        const MAX_POLL_RETRIES = 25;

        public static function analyze_fixed( $products ) {
            $result = [];

            $endpoint_url = Config::get( 'canx.cannex.endpoints.fixed' );
            $username = Config::get( 'canx.cannex.credentials.username' );
            $password = Config::get( 'canx.cannex.credentials.password' );
            $token_type = Config::get( 'canx.cannex.credentials.token_type' );
            $endpoint_function = 'canx_anty_anly_operation';

            try {
                $client = new WSSoapClient( storage_path( 'app/public/wsdl/quoting/canx_anty_anly-1.0.wsdl' ), [
                    'trace'         => true,
                    'keep_alive'    => false,
                    'cache_wsdl'    => WSDL_CACHE_NONE,
                    'exception'     => false
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
                    'transaction_id'   => uuid_create(),
                    'analysis_request' => $products
                )
            );

            try {
                $response = $client->__call( $endpoint_function, $query );

                if ( ( $response ) && ( property_exists( $response, 'analysis_response' ) ) ) {
                    if ( !is_array( $response->analysis_response ) ) {
                        $result[] = $response->analysis_response;
                    } else {
                        $result = $response->analysis_response;
                    }
                }
            } catch ( \SoapFault $exception ) {
                // error
                // TODO: Log it
                error_log( '-- DIAG: query_fixed() ---------------------' );
                error_log( '-- LAST REQUEST ----------------------------' );
                error_log( print_r( $client->__getLastRequest(), true ) );
                error_log( '-- LAST RESPONSE ---------------------------' );
                error_log( print_r( $client->__getLastResponse(), true ) );
                error_log( '-- EXCEPTION -------------------------------' );
                error_log( print_r( $exception, true ) );
            }

            return $result;
        }

        public static function analyze_fixed_zero_return( $products, $settings ) {
            $result = [];

            $endpoint_url = Config::get( 'canx.cannex.endpoints.illustration_zero_return' );
            $username = Config::get( 'canx.cannex.credentials.username' );
            $password = Config::get( 'canx.cannex.credentials.password' );
            $token_type = Config::get( 'canx.cannex.credentials.token_type' );
            $endpoint_function = 'canx_anty_eval_operation';

            try {
                $client = new WSSoapClient( storage_path( 'app/public/wsdl/quoting/canx_anty_eval-1.0.wsdl' ), [
                    'trace'         => true,
                    'keep_alive'    => false,
                    'cache_wsdl'    => WSDL_CACHE_NONE,
                    'exception'     => false
                ] );
                $client->__setLocation( $endpoint_url );
                $client->__setUsernameToken( $username, $password, $token_type );
            } catch ( \SoapFault $exception ) {
                error_log( print_r( $exception, true ) );

                return false;
            }

            $query = array(
                $endpoint_function => array(
                    'logon_id'         => $username,
                    'user_id'          => null,
                    'transaction_id'   => uuid_create(),
                    'evaluate_request' => $products,
                    'sequence'         => array(
                        'sequence_id' => 10000,
                        'evaluate_cd' => 'M'
                    )
                )
            );

            $deferral = $settings[ 'deferral' ];
            $deferral_months = ( ( ( $deferral + 1 ) * 12 ) + 1 );

            for ( $counter_month = 0; $counter_month < $deferral_months; $counter_month++ ) {
                $query[ $endpoint_function ][ 'sequence' ][ 'v' ][] = array(
                    '_' => $deferral_months,
                    'm' => $counter_month
                );
            }

            try {
                $response = $client->__call( $endpoint_function, $query );

                error_log( print_r( $client->__getLastRequest(), true ) );
                error_log( print_r( $client->__getLastResponse(), true ) );

                if ( ( $response ) && ( property_exists( $response, 'evaluate_response' ) ) && ( property_exists( $response->evaluate_response, 'evaluate_data' ) ) ) {
                    if ( !is_array( $response->evaluate_response ) ) {
                        $result[] = $response->evaluate_response;
                    } else {
                        $result = $response->evaluate_response;
                    }
                }
            } catch ( \SoapFault $exception ) {
                error_log( print_r( $exception, true ) );

                error_log( print_r( $client->__getLastRequest(), true ) );
                error_log( print_r( $client->__getLastResponse(), true ) );
                return false;
            }

            return $result;
        }

        public static function build_analysis_request( $product, $annuitant, $settings ) {
            /**
             * we use $product[ 'index' ][ 'deferral' ] instead of the deferral on $settings since
             * depending on the index, the deferral requested may overlap outside the bounds of our
             * historical data for that index.  when that happens, we truncate the deferral to make sure
             * we stay within the bounds of the historical data.
             *
             * so, for instance, if you request a 25 year deferral, but our index only has 20 years of data,
             * then we will truncate the deferral to 20 years in order to prevent an analysis error.
             */

            $response = [
                'contract_cd'                 => $annuitant[ 'annuity_type' ],
                'premium'                     => preg_replace( '/[^0-9.]/', '', $settings[ 'premium' ] ),
                'purchase_date'               => gmdate( 'Y-m-d\TH:i:s.v\Z' ),
                'gender_cd_primary'           => $annuitant[ 'owner_gender' ],
                'gender_cd_joint'             => $annuitant[ 'joint_gender' ],
                'purchase_age_primary'        => $annuitant[ 'owner_age' ],
                'purchase_age_joint'          => $annuitant[ 'joint_age' ],
                'income_start_age_primary'    => $annuitant[ 'owner_age' ] + $product[ 'index' ][ 'deferral' ],
                'income_start_age_joint'      => ( ( !empty( $annuitant[ 'joint_age' ] ) ) ? ( $annuitant[ 'joint_age' ] + $product[ 'index' ][ 'deferral' ] ) : null ),
                'index_time_range'            => [
                    'start_month' => $product[ 'index' ][ 'index_date_end' ]->format( 'n' ),
                    'start_year' => $product[ 'index' ][ 'index_date_end' ]->format( 'Y' ),
                    'end_month' => $product[ 'index' ][ 'index_date_start' ]->format( 'n' ),
                    'end_year' => $product[ 'index' ][ 'index_date_start' ]->format( 'Y' )
                ],
                'anty_ds_version_id'          => self::ANTY_ANLY_VERSION_ID,
                'analysis_cd'                 => $product[ 'analysis_cd' ],
                'analysis_data_id'            => $product[ 'analysis_data_id' ],
                'analysis_time_horizon_years' => ( ( isset( $product[ 'analysis_time_horizon_years' ] ) ) ? ( intval( $product[ 'analysis_time_horizon_years' ] ) + 1 ) : ( $product[ 'index' ][ 'deferral' ] + 1 ) ),
                'is_test'                     => 'N'
            ];

            return $response;
        }

        public static function build_evaluate_request( $product, $annuitant, $settings ) {
            $response = [
                'evaluate_request_a' => [
                    'evaluate_no'                 => 1,
                    'contract_cd'                 => $annuitant[ 'annuity_type' ],
                    'premium'                     => preg_replace( '/[^0-9.]/', '', $settings[ 'premium' ] ),
                    'purchase_date'               => gmdate( 'Y-m-d\TH:i:s.v\Z' ),
                    'gender_cd_primary'           => $annuitant[ 'owner_gender' ],
                    'gender_cd_joint'             => $annuitant[ 'joint_gender' ],
                    'purchase_age_primary'        => $annuitant[ 'owner_age' ],
                    'purchase_age_joint'          => $annuitant[ 'joint_age' ],
                    'income_start_age_primary'    => $annuitant[ 'owner_age' ] + $product[ 'index' ][ 'deferral' ],
                    'income_start_age_joint'      => ( ( !empty( $annuitant[ 'joint_age' ] ) ) ? ( $annuitant[ 'joint_age' ] + $product[ 'index' ][ 'deferral' ] ) : null ),
                    'income_analysis_data_id'     => $product[ 'analysis_data_id' ],
                    'evaluate_time_horizon_years' => ( ( isset( $product[ 'analysis_time_horizon_years' ] ) ) ? ( intval( $product[ 'analysis_time_horizon_years' ] ) + 1 ) : ( $product[ 'index' ][ 'deferral' ] + 1 ) ),
                    'anty_ds_version_id'          => self::ANTY_ANLY_VERSION_ID,
                    'sequence_id'                 => 10000, // TODO: increment? don't like using the same ID always
                    'is_test'                     => 'N'
                ]
            ];

            return $response;
        }

        public static function create_annuitant_profile( $transaction_id, $parameters, $sequence, $dataset ) {
            $request_id = null;

            $endpoint_url = Config::get( 'canx.cannex.endpoints.income' );
            $username = Config::get( 'canx.cannex.credentials.username' );
            $password = Config::get( 'canx.cannex.credentials.password' );
            $token_type = Config::get( 'canx.cannex.credentials.token_type' );
            $function_name = 'canx_anty_inc1_operation';

            try {
                $client = new WSSoapClient( storage_path( 'app/public/wsdl/quoting/canx_anty_inc1-1.0.wsdl' ), [
                    'trace'         => true,
                    'cache_wsdl'    => WSDL_CACHE_NONE,
                    'keep_alive'    => false,
                    'exception'     => true
                ] );

                $client->__setLocation( $endpoint_url );
                $client->__setUsernameToken( $username, $password, $token_type );

                if ( isset( $parameters[ 'analysis_cd' ] ) ) {
                    unset( $parameters[ 'analysis_cd' ] );
                }

                $arguments = array(
                    'canx_anty_inc1_operation' => array(
                        'income_request1_set' => array(
                            'logon_id' => $username,
                            'user_id' => '',
                            'transaction_id' => $transaction_id,
                            'anty_ds_version_id' => self::ANTY_ANLY_VERSION_ID,
                            'analysis_data_id' => $dataset,
                            'cnx_sequence_id' => [ $sequence ], // [ 0, 1 ]
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
                } catch ( \SoapFault $exception ) {
                    error_log( print_r( $exception, true ) );
                }
            } catch ( \SoapFault $exception ) {
                error_log( print_r( $exception, true ) );
            }

            return $request_id;
        }

        public static function get_guaranteed_rates( $profile_id, $transaction_id ) {
            $result = [];

            $endpoint_url = Config::get( 'canx.cannex.endpoints.income' );
            $username = Config::get( 'canx.cannex.credentials.username' );
            $password = Config::get( 'canx.cannex.credentials.password' );
            $token_type = Config::get( 'canx.cannex.credentials.token_type' );
            $function_name = 'canx_anty_inc1_operation';

            try {
                $client = new WSSoapClient( storage_path( 'app/public/wsdl/quoting/canx_anty_inc1-1.0.wsdl' ), [
                    'trace'         => true,
                    'cache_wsdl'    => WSDL_CACHE_NONE,
                    'keep_alive'    => false,
                    'exception'     => false
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

                if ( isset( $arguments[ 'analysis_cd' ] ) ) {
                    unset( $arguments[ 'analysis_cd' ] );
                }

                $retry_count = 0;

                while ( true ) {
                    try {
                        $analysis = $client->__call( $function_name, $arguments );

                        error_log( sprintf( '[+] Request status: %s', $analysis->income_response1_set->status_cd ) . PHP_EOL );

                        if ( $analysis->income_response1_set->status_cd === 'P' ) {
                            error_log( '[+] Request pending, polling in 2 seconds...' . PHP_EOL );

                            sleep(2);
                        } else {
                            error_log( print_r( $client->__getLastRequest(), true ) );
                            error_log( print_r( $client->__getLastResponse(), true ) );
                            $result = $analysis->income_response1_set;
                            break;
                        }
                    } catch ( \SoapFault $exception ) {
                        error_log( print_r( $exception, true ) );
                    }

                    $retry_count++;

                    if ( $retry_count >= self::MAX_POLL_RETRIES ) {
                        break;
                    }
                }
            } catch ( \SoapFault $exception ) {
                error_log( print_r( $exception, true ) );
                return false;
            }

            return $result;
        }
    }

