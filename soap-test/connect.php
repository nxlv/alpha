<?php
    /**
     * This class can add WSSecurity authentication support to SOAP clients
     * implemented with the PHP 5 SOAP extension.
     *
     * It extends the PHP 5 SOAP client support to add the necessary XML tags to
     * the SOAP client requests in order to authenticate on behalf of a given
     * user with a given password.
     *
     * This class was tested with Axis, WSS4J servers and CXF.
     *
     * @author Roger Veciana - http://www.phpclasses.org/browse/author/233806.html
     * @author John Kary <johnkary@gmail.com>
     * @author Alberto Martínez  - https://gist.github.com/Turin86/5569152
     * @see http://stackoverflow.com/questions/2987907/how-to-implement-ws-security-1-1-in-php5
     */
    class WSSoapClient extends SoapClient
    {
        private $OASIS = 'http://docs.oasis-open.org/wss/2004/01';

        /**
         * WS-Security Username
         * @var string
         */
        private $username;

        /**
         * WS-Security Password
         * @var string
         */
        private $password;

        /**
         * WS-Security Nonce
         * @var string
         */
        private $nonce;

        /**
         * WS-Security PasswordType
         * @var string
         */
        private $passwordType;

        /**
         * Set WS-Security credentials
         *
         * @param string $username
         * @param string $password
         * @param string $passwordType
         */
        public function __setUsernameToken($username, $password, $passwordType)
        {
            $this->username = $username;
            $this->password = $password;
            $this->passwordType = $passwordType;
        }

        /**
         * Overwrites the original method adding the security header.
         * As you can see, if you want to add more headers, the method needs to be modified.
         */
        public function __call($function_name, $arguments)
        {
            $this->__setSoapHeaders($this->generateWSSecurityHeader());
            return parent::__call($function_name, $arguments);
        }

        /**
         * Generate password digest.
         *
         * Using the password directly may work also, but it's not secure to transmit it without encryption.
         * And anyway, at least with axis+wss4j, the nonce and timestamp are mandatory anyway.
         *
         * @return string   base64 encoded password digest
         */
        private function generatePasswordDigest()
        {
            $this->nonce = uniqid();
            $this->timestamp = gmdate('Y-m-d\TH:i:s.v\Z');

            $digest = base64_encode( sha1( $this->nonce . $this->timestamp . $this->password, true ) );

            return $digest;
        }

        /**
         * Generates WS-Security headers
         *
         * @return SoapHeader
         */
        private function generateWSSecurityHeader()
        {
            if ($this->passwordType === 'PasswordDigest')
            {
                $password = $this->generatePasswordDigest();
            }
            elseif ($this->passwordType === 'PasswordText')
            {
                $password = $this->password;
                $nonce = sha1(mt_rand());
            }
            else
            {
                return '';
            }

            $xml = '
    <wsse:Security SOAP-ENV:mustUnderstand="1" xmlns:wsse="' . $this->OASIS . '/oasis-200401-wss-wssecurity-secext-1.0.xsd">
        <wsse:UsernameToken>
        <wsse:Username>' . $this->username . '</wsse:Username>
        <wsse:Password Type="' . $this->OASIS . '/oasis-200401-wss-username-token-profile-1.0#' . $this->passwordType . '">' . $password . '</wsse:Password>
        <wsse:Nonce EncodingType="' . $this->OASIS . '/oasis-200401-wss-soap-message-security-1.0#Base64Binary">' . base64_encode( $this->nonce ) . '</wsse:Nonce>';

            if ($this->passwordType === 'PasswordDigest')
            {
                $xml .= "\n\t" . '<wsu:Created xmlns:wsu="' . $this->OASIS . '/oasis-200401-wss-wssecurity-utility-1.0.xsd">' . $this->timestamp . '</wsu:Created>';
            }

            $xml .= '
        </wsse:UsernameToken>
    </wsse:Security>';

            return new SoapHeader(
                $this->OASIS . '/oasis-200401-wss-wssecurity-secext-1.0.xsd',
                'Security',
                new SoapVar($xml, XSD_ANYXML),
                true);
        }
    }
?>
<?php
    $username = 'AAUAT02';
    $password = '1N0FGV0K4N048QE4Y0V3RY2MJB7XPJBS';
    $passwordType = 'PasswordDigest';

    $url = 'file://' . dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'canx_antu-2.0.wsdl';
    //$url = 'https://wwwdev.cannex.com/devext/CANX/AntuService';

    $client = new WSSoapClient( $url, array( 'trace' => 1, 'exception' => 0 ) );
    $client->__setLocation( 'https://wwwdev.cannex.com/devext/CANX/AntuService' );
    $client->__setUsernameToken( $username, $password, $passwordType );

    $function_name = 'canx_antu_operation';        // name of the function/request
    $arguments = array(
        'canx_antu_operation' => array(
           'logon_id' => 'AAUAT02',
           'user_id' => null,
           'app' => 'CANX',
           'birth_date' => '1944-12-30',
           'joint_birth_date' => '1947-10-15',
           'owner_birth_date' => '1944-12-30',
           'premium_purchase_date' => gmdate( 'Y-m-d' ),
           'first_payment_date' => gmdate( 'Y-m-d', gmmktime() + ( 86400 * 30 ) ),
           'fund_type_cd' => 'N',
           'return_of_premium_cd' => 'GT',
           'guarantee_cd' => 'RG',
           'guarantee_year' => 10,
           'guarantee_month' => 0,
           'indicate_impaired' => null,
           /*
           'cost_basis' => 100000.00,
           'premium' => 100000.00,
           'income' => 0.0,
           */
           'cost_basis' => null,
           'premium' => null,
           'income' => 500.00,
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
           'region_cd' => 'CA',
           'region_issued_cd' => 'CA',
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
           'exclude_not_rated' => 'N',
           'is_test' => 'N'
        )
    );

    print_r( $client->__getTypes() );
    print_r( $client->__getFunctions() );

    try {
        $result = $client->__call( $function_name, $arguments );

        var_dump( $result );
    } catch ( SoapFault $exception ) {
        print_r( $exception );

        print_r( $client->__getLastResponse() );
        print_r( $client->__getLastRequest() );
    }
