<?php

return [
    /**
     *
     * Income Benefit Profiles
     * file: anty_ds_ib_prfl-[x].[y].xml
     *
     */
    'data' => [
        'node' => 'income_benefit_profile',
        'filename' => 'anty_ds_ib_prfl-1.1.xml',
        'table_name' => 'income_benefits',
        'child_tables' => [
            [
                'data' => [
                    'node' => 'info',
                    'table_name' => 'income_benefits_meta',
                    'element' => 'canx_info'
                ],
                'fields' => [
                    'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                    'key' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'key' ],
                    'value' => [ 'type' => 'node', 'target' => '_self' ]
                ]
            ],
            [
                'data' => [
                    'node' => null,
                    'table_name' => 'income_benefits_product_associations',
                    'element' => 'product_association_type'
                ],
                'fields' => [
                    'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'instance_id' ],
                    'type' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'cd' ],
                ]
            ],
            [
                'data' => [
                    'node' => 'state_cd_name',
                    'table_name' => 'income_benefits_states',
                    'element' => 'state_cd_name_list'
                ],
                'fields' => [
                    'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                    'list_cd' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'state_cd_name_list', 'key' => 'list_cd' ],
                    'state_cd' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'state_cd' ]
                ]
            ],
            [
                'data' => [
                    'node' => 'age_range',
                    'table_name' => 'income_benefits_issue_ages_owner',
                    'element' => 'issue_age_owner_instance'
                ],
                'fields' => [
                    'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'issue_age_owner_instance', 'key' => 'instance_id' ],
                    'min_years' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'min_years', 'data_type' => 'smallInteger' ],
                    'min_months' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'min_months', 'data_type' => 'smallInteger' ],
                    'max_years' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'max_years', 'data_type' => 'smallInteger' ],
                    'max_months' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'max_months', 'data_type' => 'smallInteger' ],
                ]
            ],
            [
                'data' => [
                    'node' => 'age_range',
                    'table_name' => 'income_benefits_issue_ages_annuitant',
                    'element' => 'issue_age_annuitant_instance'
                ],
                'fields' => [
                    'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'issue_age_owner_instance', 'key' => 'instance_id' ],
                    'min_years' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'min_years', 'data_type' => 'smallInteger' ],
                    'min_months' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'min_months', 'data_type' => 'smallInteger' ],
                    'max_years' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'max_years', 'data_type' => 'smallInteger' ],
                    'max_months' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'max_months', 'data_type' => 'smallInteger' ],
                ]
            ],
            [
                'data' => [
                    'node' => 'age_range',
                    'table_name' => 'income_benefits_income_start_ages',
                    'element' => 'income_start_age_instance'
                ],
                'fields' => [
                    'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'income_start_age_instance', 'key' => 'instance_id' ],
                    'min_years' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'min_years', 'data_type' => 'smallInteger' ],
                    'min_months' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'min_months', 'data_type' => 'smallInteger' ],
                    'max_years' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'max_years', 'data_type' => 'smallInteger' ],
                    'max_months' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'max_months', 'data_type' => 'smallInteger' ],
                ]
            ],
            [
                'data' => [
                    'node' => 'period_range',
                    'table_name' => 'income_benefits_income_deferral_periods',
                    'element' => 'income_deferral_period_instance'
                ],
                'fields' => [
                    'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'income_start_age_instance', 'key' => 'instance_id' ],
                    'min_years' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'min_years', 'data_type' => 'smallInteger' ],
                    'min_months' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'min_months', 'data_type' => 'smallInteger' ],
                    'max_years' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'max_years', 'data_type' => 'smallInteger' ],
                    'max_months' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'max_months', 'data_type' => 'smallInteger' ],
                ]
            ],
            [
                'data' => [
                    'node' => 'tier_rate_frequency',
                    'table_name' => 'income_benefits_current_rider_fees',
                    'element' => 'current_rider_fees_instance'
                ],
                'fields' => [
                    'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'current_rider_fees_instance', 'key' => 'instance_id' ],
                    'text_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'current_rider_fees_instance', 'key' => 'text_id' ],
                    'interest_type' => [ 'type' => 'attribute', 'target' => '_root:current_rider_fees_instance', 'node' => 'interest_type', 'key' => 'cd' ],
                    'period_months' => [ 'type' => 'attribute', 'target' => '_root:current_rider_fees_instance', 'node' => 'period', 'key' => 'months' ],
                    'period_years' => [ 'type' => 'attribute', 'target' => '_root:current_rider_fees_instance', 'node' => 'period', 'key' => 'years' ],
                    'tier_no' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'tier_no', 'data_type' => 'smallInteger' ],
                    'rate' => [ 'type' => 'node', 'key' => 'rate', 'data_type' => 'float' ],
                    'calculation_frequency' => [ 'type' => 'attribute', 'node' => 'calculation_frequency', 'key' => 'cd' ],
                ]
            ],
            [
                'data' => [
                    'node' => 'tier_rate_frequency',
                    'table_name' => 'income_benefits_min_rider_fees',
                    'element' => 'min_rider_fees_instance'
                ],
                'fields' => [
                    'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'min_rider_fees_instance', 'key' => 'instance_id' ],
                    'text_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'min_rider_fees_instance', 'key' => 'text_id' ],
                    'interest_type' => [ 'type' => 'attribute', 'target' => '_root:min_rider_fees_instance', 'node' => 'interest_type', 'key' => 'cd' ],
                    'period_months' => [ 'type' => 'attribute', 'target' => '_root:min_rider_fees_instance', 'node' => 'period', 'key' => 'months' ],
                    'period_years' => [ 'type' => 'attribute', 'target' => '_root:min_rider_fees_instance', 'node' => 'period', 'key' => 'years' ],
                    'tier_no' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'tier_no', 'data_type' => 'smallInteger' ],
                    'rate' => [ 'type' => 'node', 'key' => 'rate', 'data_type' => 'float' ],
                    'calculation_frequency' => [ 'type' => 'attribute', 'node' => 'calculation_frequency', 'key' => 'cd' ],
                ]
            ],
            [
                'data' => [
                    'node' => 'tier_rate_frequency',
                    'table_name' => 'income_benefits_max_rider_fees',
                    'element' => 'max_rider_fees_instance'
                ],
                'fields' => [
                    'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'max_rider_fees_instance', 'key' => 'instance_id' ],
                    'text_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'max_rider_fees_instance', 'key' => 'text_id' ],
                    'interest_type' => [ 'type' => 'attribute', 'target' => '_root:max_rider_fees_instance', 'node' => 'interest_type', 'key' => 'cd' ],
                    'period_months' => [ 'type' => 'attribute', 'target' => '_root:max_rider_fees_instance', 'node' => 'period', 'key' => 'months' ],
                    'period_years' => [ 'type' => 'attribute', 'target' => '_root:max_rider_fees_instance', 'node' => 'period', 'key' => 'years' ],
                    'tier_no' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'tier_no', 'data_type' => 'smallInteger' ],
                    'rate' => [ 'type' => 'node', 'key' => 'rate', 'data_type' => 'float' ],
                    'calculation_frequency' => [ 'type' => 'attribute', 'node' => 'calculation_frequency', 'key' => 'cd' ],
                ]
            ],
            [
                'data' => [
                    'node' => 'tier_rate_frequency',
                    'table_name' => 'income_benefits_step_ups',
                    'element' => 'step_ups_instance'
                ],
                'fields' => [
                    'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'step_ups_instance', 'key' => 'instance_id' ],
                    'text_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'step_ups_instance', 'key' => 'text_id' ],
                    'interest_type' => [ 'type' => 'attribute', 'target' => '_root:step_ups_instance', 'node' => 'interest_type', 'key' => 'cd' ],
                    'period_months' => [ 'type' => 'attribute', 'target' => '_root:step_ups_instance', 'node' => 'period', 'key' => 'months' ],
                    'period_years' => [ 'type' => 'attribute', 'target' => '_root:step_ups_instance', 'node' => 'period', 'key' => 'years' ],
                    'tier_no' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'tier_no', 'data_type' => 'smallInteger' ],
                    'rate' => [ 'type' => 'node', 'key' => 'rate', 'data_type' => 'float' ],
                    'calculation_frequency' => [ 'type' => 'attribute', 'node' => 'calculation_frequency', 'key' => 'cd' ],
                ]
            ],
            [
                'data' => [
                    'node' => 'tier_rate_frequency',
                    'table_name' => 'income_benefits_roll_ups',
                    'element' => 'roll_ups_instance'
                ],
                'fields' => [
                    'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'roll_ups_instance', 'key' => 'instance_id' ],
                    'text_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'roll_ups_instance', 'key' => 'text_id' ],
                    'interest_type' => [ 'type' => 'attribute', 'target' => '_root:roll_ups_instance', 'node' => 'interest_type', 'key' => 'cd' ],
                    'period_months' => [ 'type' => 'attribute', 'target' => '_root:roll_ups_instance', 'node' => 'period', 'key' => 'months' ],
                    'period_years' => [ 'type' => 'attribute', 'target' => '_root:roll_ups_instance', 'node' => 'period', 'key' => 'years' ],
                    'tier_no' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'tier_no', 'data_type' => 'smallInteger' ],
                    'rate' => [ 'type' => 'node', 'key' => 'rate', 'data_type' => 'float' ],
                    'calculation_frequency' => [ 'type' => 'attribute', 'node' => 'calculation_frequency', 'key' => 'cd' ],
                ]
            ],
            [
                'data' => [
                    'node' => null,
                    'table_name' => null,
                    'element' => 'withdrawal_tables_instance',
                    'child_tables' => [
                        [
                            'data' => [
                                'node' => 'tier_withdrawal_table',
                                'table_name' => 'income_benefits_withdrawal_tables_tiers',
                                'element' => 'withdrawal_tables',
                                'child_tables' => [
                                    [
                                        'data' => [
                                            'node' => 'age_range_rate',
                                            'table_name' => 'income_benefits_withdrawal_tables_deferral_age_ranges',
                                            'element' => 'deferral_years_age_range_rate',
                                        ],
                                        'fields' => [
                                            'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                                            'tier_id' => [ 'type' => 'variable', 'variable' => '@id:income_benefits_withdrawal_tables_tiers', 'data_type' => 'bigInteger' ],
                                            'min_years' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'min_years', 'data_type' => 'smallInteger' ],
                                            'min_months' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'min_months', 'data_type' => 'smallInteger' ],
                                            'max_years' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'max_years', 'data_type' => 'smallInteger' ],
                                            'max_months' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'max_months', 'data_type' => 'smallInteger' ],
                                            'rate' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'rate', 'data_type' => 'float' ],
                                        ]
                                    ]
                                ]
                            ],
                            'fields' => [
                                'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                                'instance_id' => [ 'type' => 'attribute', 'target' => '_parent', 'key' => 'instance_id' ],
                                'rule_id' => [ 'type' => 'attribute', 'target' => '_parent', 'key' => 'rule_id' ],
                                'tier_no' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'tier_no', 'data_type' => 'smallInteger' ],
                                'withdrawal_table_type' => [ 'type' => 'attribute', 'node' => 'withdrawal_table_type', 'key' => 'cd' ],
                                'multi_year_income_deferral_table' => [ 'type' => 'attribute', 'node' => 'multi_year_income_deferral_table', 'key' => 'cd' ],
                                'deferral_years' => [ 'type' => 'attribute', 'target' => '_self', 'node' => 'deferral_years_age_range_rate', 'key' => 'deferral_years', 'data_type' => 'smallInteger' ]
                            ]
                        ]
                    ]
                ],
                'fields' => [
                    'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'instance_id' ],
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'rule_id' ],
                ]
            ],
            [
                'data' => [
                    'node' => null,
                    'table_name' => null,
                    'element' => 'withdrawal_ruin_tables_instance',
                    'child_tables' => [
                        [
                            'data' => [
                                'node' => 'tier_withdrawal_table',
                                'table_name' => 'income_benefits_withdrawal_ruin_tables_tiers',
                                'element' => 'withdrawal_tables',
                                'child_tables' => [
                                    [
                                        'data' => [
                                            'node' => 'age_range_rate',
                                            'table_name' => 'income_benefits_withdrawal_ruin_tables_deferral_age_ranges',
                                            'element' => 'deferral_years_age_range_rate',
                                        ],
                                        'fields' => [
                                            'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                                            'tier_id' => [ 'type' => 'variable', 'variable' => '@id:income_benefits_withdrawal_ruin_tables_tiers', 'data_type' => 'bigInteger' ],
                                            'min_years' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'min_years', 'data_type' => 'smallInteger' ],
                                            'min_months' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'min_months', 'data_type' => 'smallInteger' ],
                                            'max_years' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'max_years', 'data_type' => 'smallInteger' ],
                                            'max_months' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'max_months', 'data_type' => 'smallInteger' ],
                                            'rate' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'rate', 'data_type' => 'float' ],
                                        ]
                                    ]
                                ]
                            ],
                            'fields' => [
                                'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                                'instance_id' => [ 'type' => 'attribute', 'target' => '_parent', 'key' => 'instance_id' ],
                                'rule_id' => [ 'type' => 'attribute', 'target' => '_parent', 'key' => 'rule_id' ],
                                'tier_no' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'tier_no', 'data_type' => 'smallInteger' ],
                                'withdrawal_table_type' => [ 'type' => 'attribute', 'node' => 'withdrawal_table_type', 'key' => 'cd' ],
                                'multi_year_income_deferral_table' => [ 'type' => 'attribute', 'node' => 'multi_year_income_deferral_table', 'key' => 'cd' ],
                                'deferral_years' => [ 'type' => 'attribute', 'target' => '_self', 'node' => 'deferral_years_age_range_rate', 'key' => 'deferral_years', 'data_type' => 'smallInteger' ]
                            ]
                        ]
                    ]
                ],
                'fields' => [
                    'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'instance_id' ],
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'rule_id' ],
                ]
            ],
            [
                'data' => [
                    'node' => 'premium',
                    'table_name' => 'income_benefits_initial_premiums',
                    'element' => 'initial_premium_instance'
                ],
                'fields' => [
                    'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'initial_premium_instance', 'key' => 'instance_id' ],
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'initial_premium_instance', 'key' => 'rule_id' ],
                    'text_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'initial_premium_instance', 'key' => 'text_id' ],
                    'premium_instance_id' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'instance_id' ],
                    'premium_rule_id' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'rule_id' ],
                ]
            ],
            [
                'data' => [
                    'node' => 'premium',
                    'table_name' => 'income_benefits_max_premiums',
                    'element' => 'max_premium_instance'
                ],
                'fields' => [
                    'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'max_premium_instance', 'key' => 'instance_id' ],
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'max_premium_instance', 'key' => 'rule_id' ],
                    'text_id' => [ 'type' => 'attribute', 'target' => '_root', 'node' => 'max_premium_instance', 'key' => 'text_id' ],
                    'premium_instance_id' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'instance_id' ],
                    'premium_rule_id' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'rule_id' ],
                ]
            ],
            [
                'data' => [
                    'node' => null,
                    'table_name' => 'income_benefits_premium_bonuses',
                    'element' => 'premium_bonus_instance'
                ],
                'fields' => [
                    'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'instance_id' ],
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'rule_id' ],
                    'text_id' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'text_id' ],
                    'tier_no' => [ 'type' => 'attribute', 'node' => 'tier_rate', 'key' => 'tier_no', 'data_type' => 'smallInteger' ],
                    'rate' => [ 'type' => 'attribute', 'node' => 'tier_rate', 'key' => 'rate', 'data_type' => 'float' ],
                    'months' => [ 'type' => 'attribute', 'node' => 'period', 'key' => 'months' ],
                    'years' => [ 'type' => 'attribute', 'node' => 'period', 'key' => 'years' ],
                    'frequency_inheritance' => [ 'type' => 'attribute', 'node' => 'frequency_inheritance', 'key' => 'cd' ],
                ]
            ],
            [
                'data' => [
                    'node' => null,
                    'table_name' => 'income_benefits_premium_multipliers',
                    'element' => 'premium_multiplier_instance'
                ],
                'fields' => [
                    'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'instance_id' ],
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'rule_id' ],
                    'text_id' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'text_id' ],
                    'tier_no' => [ 'type' => 'attribute', 'node' => 'tier_rate', 'key' => 'tier_no', 'data_type' => 'smallInteger' ],
                    'rate' => [ 'type' => 'attribute', 'node' => 'tier_rate', 'key' => 'rate', 'data_type' => 'float' ],
                    'months' => [ 'type' => 'attribute', 'node' => 'period', 'key' => 'months' ],
                    'years' => [ 'type' => 'attribute', 'node' => 'period', 'key' => 'years' ],
                    'frequency_inheritance' => [ 'type' => 'attribute', 'node' => 'frequency_inheritance', 'key' => 'cd' ],
                ]
            ],
            [
                'data' => [
                    'node' => null,
                    'table_name' => 'income_benefits_persistency_credits',
                    'element' => 'persistency_credit_instance'
                ],
                'fields' => [
                    'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                    'instance_id' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'instance_id' ],
                    'rule_id' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'rule_id' ],
                    'text_id' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'text_id' ],
                    'tier_no' => [ 'type' => 'attribute', 'node' => 'tier_rate', 'key' => 'tier_no', 'data_type' => 'smallInteger' ],
                    'rate' => [ 'type' => 'attribute', 'node' => 'tier_rate', 'key' => 'rate', 'data_type' => 'float' ],
                    'months' => [ 'type' => 'attribute', 'node' => 'period', 'key' => 'months' ],
                    'years' => [ 'type' => 'attribute', 'node' => 'period', 'key' => 'years' ],
                    'frequency_inheritance' => [ 'type' => 'attribute', 'node' => 'frequency_inheritance', 'key' => 'cd' ],
                ]
            ],
            [
                'data' => [
                    'node' => null,
                    'table_name' => null,
                    'element' => 'interest_crediting_instance',
                    'child_tables' => [
                        [
                            'data' => [
                                'node' => null,
                                'table_name' => 'income_benefits_interest_crediting',
                                'element' => 'tier_rate_frequency'
                            ],
                            'fields' => [
                                'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                                'instance_id' => [ 'type' => 'attribute', 'target' => '_parent', 'key' => 'instance_id' ],
                                'rule_id' => [ 'type' => 'attribute', 'target' => '_parent', 'key' => 'rule_id' ],
                                'tier_no' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'tier_no', 'data_type' => 'smallInteger' ],
                                'rate' => [ 'type' => 'node', 'key' => 'rate', 'data_type' => 'float' ],
                                'frequency_inheritance' => [ 'type' => 'attribute', 'target' => '_parent', 'node' => 'frequency_inheritance', 'key' => 'cd' ],
                                'period_months' => [ 'type' => 'attribute', 'target' => '_parent', 'node' => 'period', 'key' => 'months' ],
                                'period_years' => [ 'type' => 'attribute', 'target' => '_parent', 'node' => 'period', 'key' => 'years' ],
                                'calculation_frequency' => [ 'type' => 'attribute', 'node' => 'calculation_frequency', 'key' => 'cd' ],
                                'crediting_frequency' => [ 'type' => 'attribute', 'node' => 'crediting_frequency', 'key' => 'cd' ],
                            ]
                        ]
                    ]
                ],
                'fields' => [
                ]
            ],
            [
                'data' => [
                    'node' => null,
                    'table_name' => null,
                    'element' => 'interest_bonus_crediting_instance',
                    'child_tables' => [
                        [
                            'data' => [
                                'node' => null,
                                'table_name' => 'income_benefits_interest_bonus_crediting',
                                'element' => 'tier_rate_frequency'
                            ],
                            'fields' => [
                                'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                                'instance_id' => [ 'type' => 'attribute', 'target' => '_parent', 'key' => 'instance_id' ],
                                'rule_id' => [ 'type' => 'attribute', 'target' => '_parent', 'key' => 'rule_id' ],
                                'tier_no' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'tier_no', 'data_type' => 'smallInteger' ],
                                'rate' => [ 'type' => 'node', 'key' => 'rate', 'data_type' => 'float' ],
                                'frequency_inheritance' => [ 'type' => 'attribute', 'target' => '_parent', 'node' => 'frequency_inheritance', 'key' => 'cd' ],
                                'period_months' => [ 'type' => 'attribute', 'target' => '_parent', 'node' => 'period', 'key' => 'months' ],
                                'period_years' => [ 'type' => 'attribute', 'target' => '_parent', 'node' => 'period', 'key' => 'years' ],
                                'calculation_frequency' => [ 'type' => 'attribute', 'node' => 'calculation_frequency', 'key' => 'cd' ],
                                'crediting_frequency' => [ 'type' => 'attribute', 'node' => 'crediting_frequency', 'key' => 'cd' ],
                            ]
                        ]
                    ]
                ],
                'fields' => [
                ]
            ],
            [
                'data' => [
                    'node' => null,
                    'table_name' => null,
                    'element' => 'interest_multiplier_crediting_instance',
                    'child_tables' => [
                        [
                            'data' => [
                                'node' => null,
                                'table_name' => 'income_benefits_interest_multiplier_crediting',
                                'element' => 'tier_rate_frequency'
                            ],
                            'fields' => [
                                'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
                                'instance_id' => [ 'type' => 'attribute', 'target' => '_parent', 'key' => 'instance_id' ],
                                'rule_id' => [ 'type' => 'attribute', 'target' => '_parent', 'key' => 'rule_id' ],
                                'tier_no' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'tier_no', 'data_type' => 'smallInteger' ],
                                'rate' => [ 'type' => 'node', 'key' => 'rate', 'data_type' => 'float' ],
                                'frequency_inheritance' => [ 'type' => 'attribute', 'target' => '_parent', 'node' => 'frequency_inheritance', 'key' => 'cd' ],
                                'period_months' => [ 'type' => 'attribute', 'target' => '_parent', 'node' => 'period', 'key' => 'months' ],
                                'period_years' => [ 'type' => 'attribute', 'target' => '_parent', 'node' => 'period', 'key' => 'years' ],
                                'calculation_frequency' => [ 'type' => 'attribute', 'node' => 'calculation_frequency', 'key' => 'cd' ],
                                'crediting_frequency' => [ 'type' => 'attribute', 'node' => 'crediting_frequency', 'key' => 'cd' ],
                            ]
                        ]
                    ]
                ],
                'fields' => [
                ]
            ],
        ]
    ],
    'fields' => [
        'income_benefit_profile_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'income_benefit_profile_id' ],
        'name' => [ 'type' => 'node', 'key' => 'name' ],
        'date_open' => [ 'type' => 'node', 'key' => 'open_date', 'data_type' => 'date' ],
        'date_closed' => [ 'type' => 'node', 'key' => 'closed_date', 'data_type' => 'date' ],
        'income_benefit_type' => [ 'type' => 'attribute', 'node' => 'income_benefit_type', 'key' => 'cd' ],
        'income_benefit_proprietary_type' => [ 'type' => 'attribute', 'node' => 'proprietary_income_benefit_type', 'key' => 'cd' ],
        'income_start_age' => [ 'type' => 'attribute', 'node' => 'income_start_age', 'key' => 'cd' ],
        'income_start_age_basis' => [ 'type' => 'attribute', 'node' => 'income_start_age_basis', 'key' => 'cd' ],
        'issue_age_inheritance' => [ 'type' => 'attribute', 'node' => 'issue_age_inheritance', 'key' => 'cd' ],
        'issue_age_joint_availability' => [ 'type' => 'attribute', 'node' => 'issue_age_joint_availability', 'key' => 'cd' ],
        'issue_age_joint_owner_rules_min_age' => [ 'type' => 'attribute', 'target' => 'issue_age_joint_owner_rules', 'node' => 'min_age_basis', 'key' => 'cd' ],
        'issue_age_joint_owner_rules_max_age' => [ 'type' => 'attribute', 'target' => 'issue_age_joint_owner_rules', 'node' => 'max_age_basis', 'key' => 'cd' ],
        'issue_age_joint_annuitant_rules_min_age' => [ 'type' => 'attribute', 'target' => 'issue_age_joint_annuitant_rules', 'node' => 'min_age_basis', 'key' => 'cd' ],
        'issue_age_joint_annuitant_rules_max_age' => [ 'type' => 'attribute', 'target' => 'issue_age_joint_annuitant_rules', 'node' => 'max_age_basis', 'key' => 'cd' ],
        'issue_age_notice_text_id' => [ 'type' => 'attribute', 'node' => 'issue_age', 'key' => 'text_id' ],
        'rider_fees_notice_text_id' => [ 'type' => 'attribute', 'node' => 'step_ups', 'key' => 'text_id' ],
        'step_ups_notice_text_id' => [ 'type' => 'attribute', 'node' => 'rider_fees', 'key' => 'text_id' ],
        'premium_inheritance' => [ 'type' => 'attribute', 'node' => 'premium_inheritance', 'key' => 'cd' ],
        'premium_notice_text_id' => [ 'type' => 'attribute', 'node' => 'premium', 'key' => 'text_id' ],
        'premium_bonus_text_id' => [ 'type' => 'attribute', 'node' => 'premium_bonus', 'key' => 'text_id' ],
        'premium_multiplier_text_id' => [ 'type' => 'attribute', 'node' => 'premium_multiplier', 'key' => 'text_id' ],
        'interest_crediting_text_id' => [ 'type' => 'attribute', 'node' => 'interest_crediting', 'key' => 'text_id' ],
        'interest_bonus_crediting_text_id' => [ 'type' => 'attribute', 'node' => 'interest_bonus_crediting', 'key' => 'text_id' ],
        'interest_multiplier_crediting_text_id' => [ 'type' => 'attribute', 'node' => 'interest_multiplier_crediting', 'key' => 'text_id' ],
        'persistency_credit_text_id' => [ 'type' => 'attribute', 'node' => 'persistency_credit', 'key' => 'text_id' ],
        'income_start_age_text_id' => [ 'type' => 'attribute', 'node' => 'income_start_age', 'key' => 'text_id' ],
        'withdrawal_rates_text_id' => [ 'type' => 'attribute', 'node' => 'withdrawal_rates', 'key' => 'text_id' ],
        'withdrawal_ruin_rates_text_id' => [ 'type' => 'attribute', 'node' => 'withdrawal_ruin_rates', 'key' => 'text_id' ],
        'rmd_friendly' => [ 'type' => 'attribute', 'node' => 'rmd_friendly', 'key' => 'cd' ]
    ]
];
