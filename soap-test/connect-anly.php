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
    //$username = 'AAUAT02';
    //$password = '1N0FGV0K4N048QE4Y0V3RY2MJB7XPJBS';
    $username = 'AALLC02';
    $password = 'KKCK3NN3OF9LOI59LVLS90SF9EBO4JCN';
    $passwordType = 'PasswordDigest';

    $url = 'file://' . dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'canx_anty_anly-1.0.wsdl';
    //$endpoint_url = 'https://wwwdev.cannex.com/devext/CANX/AntyAnlyService';
    $endpoint_url = 'https://www.cannex.com/app/CANX/AntyAnlyService';

    $client = new WSSoapClient( $url, array( 'trace' => 1, 'exception' => 0 ) );
    $client->__setLocation( $endpoint_url );
    $client->__setUsernameToken( $username, $password, $passwordType );

    $function_name = 'canx_anty_anly_operation';        // name of the function/request
    $arguments = array(
        'canx_anty_anly_operation' => array(
            'logon_id' => $username,
            'user_id' => null,
            'transaction_id' => null,
            'analysis_request' => array(
                array(
                    'contract_cd' => 'S',
                    'premium' => 100000.00,
                    'purchase_date' => gmdate( 'Y-m-d\TH:i:s.v\Z' ),
                    'gender_cd_primary' => 'M',
                    'gender_cd_joint' => '',
                    'purchase_age_primary' => 55,
                    'purchase_age_joint' => null,
                    'income_start_age_primary' => 70,
                    'income_start_age_joint' => null,
                    'index_time_range' => null,
	                'anty_ds_version_id' => 'BY13MD',
                    'analysis_cd' => 'B',
                    'analysis_data_id' => '0002284235',
                    'analysis_time_horizon_years' => 20,
                    'is_test' => 'Y'
                )
            )
        )
    );

    /*
    $arguments = '
    <ws:anty_analysis_requests version="1.0">
         <can:logon_id>AAUAT02</can:logon_id>
         <can:user_id />
         <can:transaction_id />
         <can:analysis_request analysis_no="1">
            <can:contract_cd>S</can:contract_cd>
            <can:premium>100000.00</can:premium>
            <can:purchase_date>2022-11-29</can:purchase_date>

            <can:gender_cd_primary>M</can:gender_cd_primary>
            <can:gender_cd_joint />

            <can:purchase_age_primary>55</can:purchase_age_primary>
            <can:purchase_age_joint />

            <can:income_start_age_primary>65</can:income_start_age_primary>

            <can:income_start_age_joint />
            <can:anty_ds_version_id>1</can:anty_ds_version_id>

            <can:index_time_range />
            <can:analysis_cd>I</can:analysis_cd>
            <can:analysis_data_id />
            <can:analysis_time_horizon_years>5</can:analysis_time_horizon_years>

            <can:is_test>Y</can:is_test>
         </can:analysis_request>
      </ws:anty_analysis_requests>';
    */

    // print_r( $client->__getTypes() );
    // print_r( $client->__getFunctions() );

    try {
        $result = $client->__call( $function_name, $arguments );
        //$result = $client->__doRequest( $arguments, $endpoint_url, $function_name, '1.0' );

        print_r( $result );
    } catch ( SoapFault $exception ) {
        //print_r( $result );
        //print_r( $exception );

        print_r( $exception );
        print_r( $client->__getLastResponse() );
        print_r( $client->__getLastRequest() );
    }
