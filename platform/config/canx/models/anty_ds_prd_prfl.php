<?php

return [
    /**
     *
     * Product Profile Definitions
     * file: anty_ds_prd_prfl-[x].[y].xml
     *
     */
    'data' => [
        'node' => 'product_profile',
        'filename' => 'anty_ds_prd_prfl-1.1.xml',
        'table_name' => 'products_profiles',
        'child_tables' => [
            [
                'data' => [
                    'node' => 'info',
                    'table_name' => 'products_profiles_meta',
                    'element' => 'canx_info'
                ],
                'fields' => [
                    'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                    'key' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'key' ],
                    'value' => [ 'type' => 'node', 'key' => '_self' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'state_cd_name',
                    'table_name' => 'products_profiles_states',
                    'element' => 'state_availability'
                ],
                'fields' => [
                    'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                    'code' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'state_cd' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'cd_name',
                    'table_name' => 'products_profiles_ownership_types',
                    'element' => 'ownership_type_list'
                ],
                'fields' => [
                    'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                    'code' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'cd' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'cd_name',
                    'table_name' => 'products_profiles_annuitant_types',
                    'element' => 'annuitant_type_list'
                ],
                'fields' => [
                    'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                    'code' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'cd' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'cd_name',
                    'table_name' => 'products_profiles_fund_types',
                    'element' => 'fund_type_instance'
                ],
                'fields' => [
                    'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'fund_type_instance', 'key' => 'instance_id' ],
                    'rule_id' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'rule_id' ],
                    'code' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'cd' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'cd_name',
                    'table_name' => 'products_profiles_rops',
                    'element' => 'rop_instance'
                ],
                'fields' => [
                    'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'rop_instance', 'key' => 'instance_id' ],
                    'rule_id' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'rule_id' ],
                    'code' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'cd' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'cd_name',
                    'table_name' => 'products_profiles_mvas',
                    'element' => 'mva_instance'
                ],
                'fields' => [
                    'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'mva_instance', 'key' => 'instance_id' ],
                    'rule_id' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'rule_id' ],
                    'code' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'cd' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'cdsc',
                    'table_name' => 'products_profiles_cdsc_schedules',
                    'element' => 'cdsc_schedule_instance'
                ],
                'fields' => [
                    'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'cdsc_schedule_instance', 'key' => 'instance_id' ],
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'cdsc_schedule_instance', 'key' => 'rule_id' ],
                    'years' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'years', 'data_type' => 'smallInteger' ],
                    'rate' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'rate', 'data_type' => 'float' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'cd_name',
                    'table_name' => 'products_profiles_surrender_waivers',
                    'element' => 'surrender_charge_waivers_instance'
                ],
                'fields' => [
                    'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'surrender_charge_waivers_instance', 'key' => 'instance_id' ],
                    'code' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'cd' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'tier_rate',
                    'table_name' => 'products_profiles_withdrawals_free_rates',
                    'element' => 'free_withdrawals_instance'
                ],
                'fields' => [
                    'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'free_withdrawals_instance', 'key' => 'instance_id' ],
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'free_withdrawals_instance', 'key' => 'rule_id' ],
                    'tier_no' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'tier_no' ],
                    'rate' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'rate' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'age_range',
                    'table_name' => 'products_profiles_issue_age_owner',
                    'element' => 'issue_age_owner_instance'
                ],
                'fields' => [
                    'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'issue_age_owner_instance', 'key' => 'instance_id' ],
                    'rule_id' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'rule_id' ],
                    'min_years' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'min_years', 'data_type' => 'smallInteger' ],
                    'min_months' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'min_months', 'data_type' => 'smallInteger' ],
                    'max_years' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'max_years', 'data_type' => 'smallInteger' ],
                    'max_months' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'max_months', 'data_type' => 'smallInteger' ],
                ]
            ],
            [
                'data' => [
                    'node' => 'age_range',
                    'table_name' => 'products_profiles_issue_age_annuitant',
                    'element' => 'issue_age_annuitant_instance'
                ],
                'fields' => [
                    'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'issue_age_annuitant_instance', 'key' => 'instance_id' ],
                    'rule_id' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'rule_id' ],
                    'min_years' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'min_years', 'data_type' => 'smallInteger' ],
                    'min_months' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'min_months', 'data_type' => 'smallInteger' ],
                    'max_years' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'max_years', 'data_type' => 'smallInteger' ],
                    'max_months' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'max_months', 'data_type' => 'smallInteger' ],
                ]
            ],
            [
                'data' => [
                    'node' => null,
                    'table_name' => 'products_profiles_issue_age_joint_owner_rules',
                    'element' => 'issue_age_joint_owner_rules'
                ],
                'fields' => [
                    'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                    'min_age_basis' => [ 'type' => 'attribute', 'node' => 'min_age_basis', 'key' => 'cd' ],
                    'max_age_basis' => [ 'type' => 'attribute', 'node' => 'max_age_basis', 'key' => 'cd' ]
                ]
            ],
            [
                'data' => [
                    'node' => null,
                    'table_name' => 'products_profiles_issue_age_joint_annuitant_rules',
                    'element' => 'issue_age_joint_annuitant_rules'
                ],
                'fields' => [
                    'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                    'min_age_basis' => [ 'type' => 'attribute', 'node' => 'min_age_basis', 'key' => 'cd' ],
                    'max_age_basis' => [ 'type' => 'attribute', 'node' => 'max_age_basis', 'key' => 'cd' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'premium',
                    'table_name' => 'products_profiles_initial_premiums',
                    'element' => 'initial_premium_instance'
                ],
                'fields' => [
                    'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'initial_premium_instance', 'key' => 'instance_id' ],
                    'rule_id' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'rule_id' ],
                    'value' => [ 'type' => 'node', 'key' => '_self', 'data_type' => 'decimal(12,4)' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'premium',
                    'table_name' => 'products_profiles_maximum_premiums',
                    'element' => 'max_premium_instance'
                ],
                'fields' => [
                    'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'max_premium_instance', 'key' => 'instance_id' ],
                    'rule_id' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'rule_id' ],
                    'value' => [ 'type' => 'node', 'key' => '_self', 'data_type' => 'decimal(12,4)' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'age',
                    'table_name' => 'products_profiles_annuitization_age_maximums',
                    'element' => 'max_annuitization_age_instance'
                ],
                'fields' => [
                    'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'max_annuitization_age_instance', 'key' => 'instance_id' ],
                    'rule_id' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'rule_id' ],
                    'years' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'years', 'data_type' => 'smallInteger' ],
                    'months' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'months', 'data_type' => 'smallInteger' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'tier_rate',
                    'table_name' => 'products_profiles_gmv_initial_rates',
                    'element' => 'standard_gmv_initial_rate_instance'
                ],
                'fields' => [
                    'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'standard_gmv_initial_rate_instance', 'key' => 'instance_id' ],
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'standard_gmv_initial_rate_instance', 'key' => 'rule_id' ],
                    'tier_no' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'tier_no', 'data_type' => 'integer' ],
                    'rate' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'rate', 'data_type' => 'float' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'tier_rate',
                    'table_name' => 'products_profiles_gmv_increase_rates',
                    'element' => 'standard_gmv_increase_rate_instance'
                ],
                'fields' => [
                    'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'standard_gmv_increase_rate_instance', 'key' => 'instance_id' ],
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'standard_gmv_increase_rate_instance', 'key' => 'rule_id' ],
                    'tier_no' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'tier_no', 'data_type' => 'integer' ],
                    'rate' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'rate', 'data_type' => 'float' ]
                ]
            ],
            [
                'data' => [
                    'node' => null,
                    'table_name' => 'products_profiles_riders_benefits',
                    'element' => 'other_riders_benefits_instance'
                ],
                'fields' => [
                    'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'other_riders_benefits_instance', 'key' => 'instance_id' ],
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'other_riders_benefits_instance', 'key' => 'rule_id' ],
                    'name' => [ 'type' => 'node', 'key' => 'name' ],
                    'other_riders_benefits_instance' => [ 'type' => 'attribute', 'node' => 'other_riders_benefits_type', 'key' => 'cd' ]
                ]
            ],
        ]
    ],
    'fields' => [
        'product_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'product_profile_id' ],
        'product_id' => [ 'type' => 'node', 'key' => 'product_id' ],
        'surrender_period_basis' => [ 'type' => 'attribute', 'node' => 'surrender_period_basis', 'key' => 'cd' ],
        'minimum_withdrawals_rule_applicable' => [ 'type' => 'attribute', 'node' => 'minimum_withdrawals_rule_applicable', 'key' => 'cd' ],
        'systematic_withdrawals_available' => [ 'type' => 'attribute', 'node' => 'systematic_withdrawals_available', 'key' => 'cd' ],
        'bail_out' => [ 'type' => 'attribute', 'node' => 'bail_out', 'key' => 'cd' ],
        'free_look_period' => [ 'type' => 'attribute', 'node' => 'free_look_period', 'key' => 'cd' ],
        'fees_commissions' => [ 'type' => 'attribute', 'node' => 'fees_commissions', 'key' => 'cd' ],
        'contract_continuation' => [ 'type' => 'attribute', 'node' => 'contract_continuation', 'key' => 'cd' ],
        'age_rule_basis' => [ 'type' => 'attribute', 'node' => 'age_rule_basis', 'key' => 'cd' ],
        'issue_age_joint_availability' => [ 'type' => 'attribute', 'node' => 'issue_age_joint_availability', 'key' => 'cd' ],
    ]
];
