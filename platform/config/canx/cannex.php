<?php

return [
    'credentials' => [
        'username' => env( 'CANNEX_WS_USERNAME' ),
        'password' => env( 'CANNEX_WS_PASSWORD' ),
        'token_type' => env( 'CANNEX_WS_DIGEST_TYPE' )
    ],
    'endpoints' => [
        'immediate' => env( 'CANNEX_WS_ENDPOINT_IMMEDIATE' ),
        'fixed' => env( 'CANNEX_WS_ENDPOINT_FIXED' ),
        'illustration' => env( 'CANNEX_WS_ENDPOINT_ILLUSTRATION' ),
        'illustration_zero_return' => env( 'CANNEX_WS_ENDPOINT_ILLUSTRATION_GUARANTEED' ),
        'income' => env( 'CANNEX_WS_ENDPOINT_INCOME' )
    ],
    'lookup' => [
        'carriers' => 'anty_ds_crr_prds',
        'products' => 'anty_ds_anly_data',
        'products-instances' => 'anty_ds_prd_inst',
        'products-profiles' => 'anty_ds_prd_prfl',
        'death-benefits' => 'anty_ds_db_prfl',
        'income-benefits' => 'anty_ds_ib_prfl',
        'indexes' => 'anty_ds_index',
        'notices' => 'anty_ds_text',
        'rules' => 'anty_ds_rules',
        'text' => 'anty_ds_text'
    ]
];
