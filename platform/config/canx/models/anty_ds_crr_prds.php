<?php

return [
    /**
     *
     * Carriers
     * file: anty_ds_crr_prds-[x].[y].xml
     *
     */
    'data' => [
        'node' => 'carrier_products',
        'filename' => 'anty_ds_crr_prds-1.1.xml',
        'table_name' => 'carriers',
        'child_tables' => [
            [
                'data' => [
                    'node' => 'info',
                    'table_name' => 'carriers_meta',
                    'element' => 'canx_info'
                ],
                'fields' => [
                    'carrier_id' => [ 'type' => 'variable', 'variable' => '@id:carriers', 'data_type' => 'bigInteger' ],
                    'key' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'key' ],
                    'value' => [ 'type' => 'node', 'target' => '_self' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'carrier_rating',
                    'table_name' => 'carriers_ratings',
                    'element' => null
                ],
                'fields' => [
                    'carrier_id' => [ 'type' => 'variable', 'variable' => '@id:carriers', 'data_type' => 'bigInteger' ],
                    'company' => [ 'type' => 'attribute', 'node' => 'rating_company', 'key' => 'cd' ],
                    'rating' => [ 'type' => 'attribute', 'node' => 'rating', 'key' => 'cd' ],
                    'effective_date' => [ 'type' => 'node', 'key' => 'effective_date', 'data_type' => 'date' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'product',
                    'table_name' => 'carriers_products',
                    'element' => null,
                    'child_tables' => [
                        [
                            'data' => [
                                'node' => 'info',
                                'table_name' => 'carriers_products_meta',
                                'element' => 'canx_info'
                            ],
                            'fields' => [
                                'carrier_id' => [ 'type' => 'variable', 'variable' => '@id:carriers', 'data_type' => 'bigInteger' ],
                                'carrier_product_id' => [ 'type' => 'variable', 'variable' => '@id:carriers_products', 'data_type' => 'bigInteger' ],
                                'key' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'key' ],
                                'value' => [ 'type' => 'node', 'target' => '_self' ]
                            ]
                        ]
                    ]
                ],
                'fields' => [
                    'carrier_id' => [ 'type' => 'variable', 'variable' => '@id:carriers', 'data_type' => 'bigInteger' ],
                    'product_id' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'product_id' ],
                    'name' => [ 'type' => 'node', 'key' => 'product_name' ]
                ]
            ]
        ]
    ],
    'fields' => [
        'name' => [ 'type' => 'node', 'key' => 'carrier_name' ],
        'version' => [ 'type' => 'node', 'key' => 'version_id' ]
    ]
];
