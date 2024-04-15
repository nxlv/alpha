<?php

/**
 * [X] No framework prototype
 * [X] Proof of concept code
 * [X] Integration needed
 *
 * NOT FOR USE IN PRODUCTION
 */

    require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'ws-soap-client.php';

    class CANNEX_SOAP {
        const CANNEX_USERNAME = 'AAUAT02';
        const CANNEX_PASSWORD = '1N0FGV0K4N048QE4Y0V3RY2MJB7XPJBS';
        const CANNEX_DIGEST_TYPE = 'PasswordDigest';

        private $_endpoint_url;

        private $_username;
        private $_password;
        private $_token_type;

        public $request;
        public $result;
        public $client;
        public $messages = [];

        function __construct( $wsdl, $endpoint_url ) {
            $this->_endpoint_url = $endpoint_url;
            $this->_username = self::CANNEX_USERNAME;
            $this->_password = self::CANNEX_PASSWORD;
            $this->_token_type = self::CANNEX_DIGEST_TYPE;

            $this->client = new WSSoapClient( $wsdl, [ 'trace' => 0, 'exception' => 0 ] );
            $this->client->__setLocation( $endpoint_url );
            $this->client->__setUsernameToken( $this->_username, $this->_password, $this->_token_type );
        }

        function __destruct() {
            $this->client = null;

            $this->_endpoint_url = null;
            $this->_username = null;
            $this->_password = null;
            $this->_token_type = null;
        }

        public function query( $parameters, $method = 'canx_antu_operation' ) {
            $this->request = null;
            $this->result = null;
            $this->messages = [];

            $arguments = array(
                'canx_antu_operation' => array(
                    'logon_id' => $this->_username,
                    'user_id' => null,
                    'app' => 'CANX',
                    'birth_date' => '1944-12-30',
                    'joint_birth_date' => '1947-10-15',
                    'owner_birth_date' => '1944-12-30',
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

            if ( ( isset( $parameters[ 'deferral' ] ) ) && ( intval( $parameters[ 'deferral' ] ) ) ) {
                $arguments[ 'canx_antu_operation' ][ 'guarantee_year' ] = intval( $parameters[ 'deferral' ] );
                $arguments[ 'canx_antu_operation' ][ 'first_payment_date' ] = gmdate( 'Y-m-d', gmmktime( 86400 * 30 ) );
            }

            if ( isset( $parameters[ 'mode' ] ) ) {
                $arguments[ 'canx_antu_operation' ][ 'cost_basis' ] = preg_replace( '/[^\d.]|(?<!\d)\./', '', $parameters[ 'investment' ] );
                $arguments[ 'canx_antu_operation' ][ 'premium' ] = $arguments[ 'canx_antu_operation' ][ 'cost_basis' ];
                $arguments[ 'canx_antu_operation' ][ 'income' ] = null;
            } else {
                $arguments[ 'canx_antu_operation' ][ 'cost_basis' ] = null;
                $arguments[ 'canx_antu_operation' ][ 'premium' ] = null;
                $arguments[ 'canx_antu_operation' ][ 'income' ] = preg_replace( '/[^\d.]|(?<!\d)\./', '', $parameters[ 'monthly' ] );
            }

            $this->request = $arguments;

            try {
                $this->result = $this->client->__call( $method, $arguments );

                if ( !empty( $this->result ) ) {
                    if ( isset( $this->result->survey_notes ) ) {
                        foreach ( $this->result->survey_notes as $note ) {
                            array_push( $this->messages, $note->_ );
                        }
                    }

                    if ( isset( $this->result->carrier ) ) {
                        $this->result = $this->result->carrier;
                    }
                }
            } catch ( SoapFault $exception ) {
                // error
                array_push( $this->messages, 'An error occurred while querying CANNEX.  The message was: ' . $this->client->__getLastResponse() );
            }
        }
    }