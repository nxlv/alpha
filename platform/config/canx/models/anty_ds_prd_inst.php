<?php

return [
    /**
     *
     * Product Instances
     * file: anty_ds_prd_inst-[x].[y].xml
     *
     */
    'data' => [
        'node' => 'product_instance',
        'filename' => 'anty_ds_prd_inst-1.1.xml',
        'table_name' => 'products_instances',
        'child_tables' => [
            [
                'data' => [
                    'node' => 'info',
                    'table_name' => 'products_instances_meta',
                    'element' => 'canx_info'
                ],
                'fields' => [
                    'product_instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'product_instance_id' ],
                    'key' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'key' ],
                    'value' => [ 'type' => 'node', 'target' => '_self' ]
                ]
            ],
            [
                'data' => [
                    'table_name' => 'products_instances_strategies',
                    'element' => 'strategy_details_instance',
                    'child_tables' => [
                        [
                            'data' => [
                                'node' => 'tier_rate_frequency',
                                'table_name' => 'products_instances_strategies_fees_current',
                                'element' => 'current_strategy_fees_instance'
                            ],
                            'fields' => [
                                'product_instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'product_instance_id' ],
                                'product_strategy_instance_id' => [ 'type' => 'attribute', 'target' => '_parent', 'key' => 'instance_id' ],
                                'instance_id' => [ 'type' => 'attribute', 'node' => '_iterator', 'key' => 'instance_id' ],
                                'tier_no' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'tier_no', 'data_type' => 'smallInteger' ],
                                'rate' => [ 'type' => 'node', 'key' => 'rate', 'data_type' => 'float' ],
                                'calculation_frequency' => [ 'type' => 'attribute', 'node' => 'calculation_frequency', 'key' => 'cd' ],
                            ]
                        ],
                        [
                            'data' => [
                                'node' => 'tier_rate_frequency',
                                'table_name' => 'products_instances_strategies_fees_maximum',
                                'element' => 'maximum_strategy_fees_instance'
                            ],
                            'fields' => [
                                'product_instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'product_instance_id' ],
                                'product_strategy_instance_id' => [ 'type' => 'attribute', 'target' => '_parent', 'key' => 'instance_id' ],
                                'instance_id' => [ 'type' => 'attribute', 'node' => '_iterator', 'key' => 'instance_id' ],
                                'tier_no' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'tier_no', 'data_type' => 'smallInteger' ],
                                'rate' => [ 'type' => 'node', 'key' => 'rate', 'data_type' => 'float' ],
                                'calculation_frequency' => [ 'type' => 'attribute', 'node' => 'calculation_frequency', 'key' => 'cd' ],
                            ]
                        ],
                        [
                            'data' => [
                                'node' => 'strategy_rate_instance',
                                'table_name' => 'products_instances_strategies_rates',
                                'child_tables' => [
                                    [
                                        'data' => [
                                            'element' => 'sub_strategy_details_instance',
                                            'table_name' => 'products_instances_strategies_substrategies',
                                            'child_tables' => [
                                                [
                                                    'data' => [
                                                        'node' => null,
                                                        'table_name' => 'products_instances_strategies_substrategies_rates',
                                                        'element' => 'strategy_rate_instance'
                                                    ],
                                                    'fields' => [
                                                        'product_instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'product_instance_id' ],
                                                        'product_strategy_id' => [ 'type' => 'attribute', 'target' => '_parent:_parent', 'key' => 'instance_id' ],
                                                        'instance_id' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'instance_id' ],
                                                        'current_participation_rate' => [ 'type' => 'node', 'key' => 'current_participation_rate', 'data_type' => 'float' ]
                                                    ]
                                                ]
                                            ]
                                        ],
                                        'fields' => [
                                            'product_instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'product_instance_id' ],
                                            'product_strategy_instance_id' => [ 'type' => 'attribute', 'target' => '_parent', 'key' => 'instance_id' ],
                                            'instance_id' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'instance_id' ],
                                            'strategy_type' => [ 'type' => 'attribute', 'node' => 'strategy_type', 'key' => 'cd' ],
                                            'strategy_configuration' => [ 'type' => 'attribute', 'node' => 'strategy_configuration', 'key' => 'cd' ],
                                            'index_id' => [ 'type' => 'node', 'key' => 'index_id' ],
                                            'calculation_frequency' => [ 'type' => 'attribute', 'node' => 'calculation_frequency', 'key' => 'cd' ],
                                            'strategy_range_indicator_min' => [ 'type' => 'attribute', 'node' => 'strategy_range', 'key' => 'min_indicator' ],
                                            'strategy_range_indicator_max' => [ 'type' => 'attribute', 'node' => 'strategy_range', 'key' => 'max_indicator' ],
                                            'strategy_range_period_years_min' => [ 'type' => 'attribute', 'target' => 'strategy_range', 'node' => 'period_range', 'key' => 'min_years', 'data_type' => 'smallInteger' ],
                                            'strategy_range_period_years_max' => [ 'type' => 'attribute', 'target' => 'strategy_range', 'node' => 'period_range', 'key' => 'max_years', 'data_type' => 'smallInteger' ],
                                            'strategy_range_period_months_min' => [ 'type' => 'attribute', 'target' => 'strategy_range', 'node' => 'period_range', 'key' => 'min_months', 'data_type' => 'smallInteger' ],
                                            'strategy_range_period_months_max' => [ 'type' => 'attribute', 'target' => 'strategy_range', 'node' => 'period_range', 'key' => 'max_months', 'data_type' => 'smallInteger' ],
                                        ]
                                    ]
                                ]
                            ],
                            'fields' => [
                                'product_instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'product_instance_id' ],
                                'product_strategy_instance_id' => [ 'type' => 'attribute', 'target' => '_parent', 'key' => 'instance_id' ],
                                'instance_id' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'instance_id' ],
                                'premium_range_min' => [ 'type' => 'attribute', 'node' => 'premium_range', 'key' => 'min', 'data_type' => 'decimal(12,4)' ],
                                'premium_range_max' => [ 'type' => 'attribute', 'node' => 'premium_range', 'key' => 'max', 'data_type' => 'decimal(12,4)' ],
                                'current_fixed_rate' => [ 'type' => 'node', 'key' => 'current_fixed_rate', 'data_type' => 'float' ],
                            ]
                        ]
                    ]
                ],
                'fields' => [
                    'product_instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'product_instance_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'instance_id' ],
                    'index_id' => [ 'type' => 'node', 'key' => 'index_id' ],
                    'strategy_type' => [ 'type' => 'attribute', 'node' => 'strategy_type', 'key' => 'cd' ],
                    'strategy_configuration' => [ 'type' => 'attribute', 'node' => 'strategy_type', 'key' => 'cd' ],
                    'guarantee_status' => [ 'type' => 'attribute', 'node' => 'guarantee_status', 'key' => 'cd' ],
                    'calculation_frequency' => [ 'type' => 'attribute', 'node' => 'calculation_frequency', 'key' => 'cd' ],
                    'crediting_frequency' => [ 'type' => 'attribute', 'node' => 'crediting_frequency', 'key' => 'cd' ],
                    'guarantee_period_years' => [ 'type' => 'attribute', 'node' => 'guarantee_period', 'key' => 'years', 'data_type' => 'smallInteger' ],
                    'guarantee_period_months' => [ 'type' => 'attribute', 'node' => 'guarantee_period', 'key' => 'months', 'data_type' => 'smallInteger' ],
                ]
            ]
        ]
    ],
    'fields' => [
        'product_instance_id' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'product_instance_id' ],
        'product_id' => [ 'type' => 'node', 'key' => 'product_id' ],
        'product_profile_id' => [ 'type' => 'node', 'key' => 'product_profile_id' ],
        'group_no' => [ 'type' => 'node', 'key' => 'group_no' ],
        'rate_effective_date' => [ 'type' => 'node', 'target' => 'rate_effective_date', 'key' => 'current_rates_from', 'data_type' => 'date' ]
    ]
];
