<?php

return [
    /**
     *
     * Rules
     * file: anty_ds_rules-[x].[y].xml
     *
     */
    'data' => [
        'node' => 'rule',
        'filename' => 'anty_ds_rules-1.1.xml',
        'table_name' => 'rules',
        'child_tables' => [
            [
                'data' => [
                    'node' => 'state_cd_name',
                    'table_name' => 'rules_states',
                    'element' => 'state_cd_name_list'
                ],
                'fields' => [
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'rule_id' ],
                    'list_cd' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'state_cd_name_list', 'key' => 'list_cd' ],
                    'state_cd' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'state_cd' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'cd_name',
                    'table_name' => 'rules_fund_types',
                    'element' => 'fund_type_list'
                ],
                'fields' => [
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'rule_id' ],
                    'type' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'cd' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'cd_name',
                    'table_name' => 'rules_strategies',
                    'element' => 'strategy_type_list'
                ],
                'fields' => [
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'rule_id' ],
                    'type' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'cd' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'cd_name',
                    'table_name' => 'rules_share_classes',
                    'element' => 'share_class_list'
                ],
                'fields' => [
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'rule_id' ],
                    'type' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'cd' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'period_range_instance',
                    'table_name' => 'rules_guarantee_periods',
                    'element' => 'guarantee_period_list'
                ],
                'fields' => [
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'rule_id' ],
                    'min_years' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'min_years', 'data_type' => 'smallInteger' ],
                    'min_months' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'min_months', 'data_type' => 'smallInteger' ],
                    'max_years' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'max_years', 'data_type' => 'smallInteger' ],
                    'max_months' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'max_months', 'data_type' => 'smallInteger' ],
                ]
            ],
            [
                'data' => [
                    'node' => 'income_benefit_profile_list',
                    'table_name' => 'rules_income_benefit_profiles',
                    'element' => 'instance_id'
                ],
                'fields' => [
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'rule_id' ],
                    'instance_id' => [ 'type' => 'node', 'key' => 'instance_id' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'death_benefit_profile_list',
                    'table_name' => 'rules_death_benefit_profiles',
                    'element' => 'instance_id'
                ],
                'fields' => [
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'rule_id' ],
                    'instance_id' => [ 'type' => 'node', 'key' => 'instance_id' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'cdsc_schedule_list',
                    'table_name' => 'rules_cdsc_schedules',
                    'element' => 'instance_id'
                ],
                'fields' => [
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'rule_id' ],
                    'instance_id' => [ 'type' => 'node', 'key' => 'instance_id' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'other_rider_benefits_list',
                    'table_name' => 'rules_other_rider_benefits',
                    'element' => 'instance_id'
                ],
                'fields' => [
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'rule_id' ],
                    'instance_id' => [ 'type' => 'node', 'key' => 'instance_id' ]
                ]
            ]
        ]
    ],
    'fields' => [
        'rule_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'rule_id' ],
        'contract' => [ 'type' => 'attribute', 'node' => 'contract', 'key' => 'cd' ],
        'gender' => [ 'type' => 'attribute', 'node' => 'gender', 'key' => 'cd' ],
        'premium_min' => [ 'type' => 'attribute', 'node' => 'premium_range', 'key' => 'min', 'data_type' => 'decimal(12,4)' ],
        'premium_max' => [ 'type' => 'attribute', 'node' => 'premium_range', 'key' => 'max', 'data_type' => 'decimal(12,4)' ],
        'age_range_min_years' => [ 'type' => 'attribute', 'node' => 'age_range', 'key' => 'min_years', 'data_type' => 'smallInteger' ],
        'age_range_min_months' => [ 'type' => 'attribute', 'node' => 'age_range', 'key' => 'min_months', 'data_type' => 'smallInteger' ],
        'age_range_max_years' => [ 'type' => 'attribute', 'node' => 'age_range', 'key' => 'max_years', 'data_type' => 'smallInteger' ],
        'age_range_max_months' => [ 'type' => 'attribute', 'node' => 'age_range', 'key' => 'max_months', 'data_type' => 'smallInteger' ],
        'mva_cd' => [ 'type' => 'attribute', 'node' => 'mva', 'key' => 'cd' ],
        'rop_cd' => [ 'type' => 'attribute', 'node' => 'rop', 'key' => 'cd' ],
        'strategy_fees' => [ 'type' => 'attribute', 'node' => 'strategy_fees', 'key' => 'cd' ],
    ]
];
