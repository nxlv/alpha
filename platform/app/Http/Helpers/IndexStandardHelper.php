<?php

    namespace App\Http\Helpers;

    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Config;

    class IndexStandardHelper {
        public static function consume_endpoint( $endpoint ) {
            $curl   = curl_init();
            curl_setopt( $curl, CURLOPT_URL, sprintf( '%s/%s?access_token=%s', env( 'INDEX_STANDARD_API_BASE_URL' ), $endpoint, env( 'INDEX_STANDARD_API_TOKEN' ) ) );
            curl_setopt( $curl, CURLOPT_HEADER, false );
            curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

            $response = curl_exec( $curl );

            return $response;
        }

        public static function get_all_reports() {
            $response = self::consume_endpoint( 'reports' );

            if ( !empty( $response ) ) {
                $response = json_decode( $response );

                if ( ( isset( $response->data ) ) && ( count( $response->data ) ) ) {
                    $response = $response->data;
                } else {
                    $response = [];
                }
            }

            return $response;
        }
    }
