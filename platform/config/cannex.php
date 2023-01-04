<?php

return [
    'parser' => [
        /**
         *
         * Product List
         * file: anty_ds_anly_data-[x].[y].xml
         *
         */
        'anty_ds_anly_data' => [
            'data' => [
                'node' => 'analysis_data_instance',
                'filename' => 'anty_ds_anly_data-1.1.xml',
                'table_name' => 'products',
                'child_tables' => [
                    [
                        'data' => [
                            'node' => 'info',
                            'table_name' => 'products_meta',
                            'element' => 'canx_info'
                        ],
                        'fields' => [
                            'analysis_data_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'analysis_data_id' ],
                            'key' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'key' ],
                            'value' => [ 'type' => 'node', 'key' => '_self' ]
                        ]
                    ]
                ]
            ],
            'fields' => [
                'analysis_cd' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'analysis_cd' ],
                'analysis_data_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'analysis_data_id' ],
                'rule_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'rule_id' ],
                'product_id' => [ 'type' => 'node', 'key' => 'product_id' ],
                'product_profile_id' => [ 'type' => 'node', 'key' => 'product_profile_id' ],
                'product_instance_id' => [ 'type' => 'node', 'key' => 'product_instance_id' ],
                'strategy_details_instance_id' => [ 'type' => 'node', 'key' => 'strategy_details_instance_id' ],
                'strategy_rate_instance_id' => [ 'type' => 'node', 'key' => 'strategy_rate_instance_id' ],
                'minimum_rate_instance_id' => [ 'type' => 'node', 'key' => 'minimum_rate_instance_id' ],
                'guarantee_period_months' => [ 'type' => 'attribute', 'node' => 'guarantee_period', 'key' => 'months', 'data_type' => 'smallInteger' ],
                'guarantee_period_years' => [ 'type' => 'attribute', 'node' => 'guarantee_period', 'key' => 'years', 'data_type' => 'smallInteger' ],
                'surrender_period_months' => [ 'type' => 'attribute', 'node' => 'surrender_period', 'key' => 'months', 'data_type' => 'smallInteger' ],
                'surrender_period_years' => [ 'type' => 'attribute', 'node' => 'surrender_period', 'key' => 'years', 'data_type' => 'smallInteger' ],
                'cdsc_schedule_instance_id' => [ 'type' => 'node', 'key' => 'cdsc_schedule_instance_id' ],
                'free_withdrawals_instance_id' => [ 'type' => 'node', 'key' => 'free_withdrawals_instance_id' ],
                'standard_gmv_initial_rate_instance_id' => [ 'type' => 'node', 'key' => 'standard_gmv_initial_rate_instance_id' ],
                'standard_gmv_increase_rate_instance_id' => [ 'type' => 'node', 'key' => 'standard_gmv_increase_rate_instance_id' ],
                'enhanced_gmv_initial_rate_instance_id' => [ 'type' => 'node', 'key' => 'enhanced_gmv_initial_rate_instance_id' ],
                'enhanced_gmv_increase_rate_instance_id' => [ 'type' => 'node', 'key' => 'enhanced_gmv_increase_rate_instance_id' ],
                'current_m_e_fees_instance_id' => [ 'type' => 'node', 'key' => 'current_m_e_fees_instance_id' ],
                'current_admin_fees_instance_id' => [ 'type' => 'node', 'key' => 'current_admin_fees_instance_id' ],
                'current_fund_facilitation_fees_instance_id' => [ 'type' => 'node', 'key' => 'current_fund_facilitation_fees_instance_id' ],
                'current_premium_based_fees_instance_id' => [ 'type' => 'node', 'key' => 'current_premium_based_fees_instance_id' ],
                'current_other_fees_instance_id' => [ 'type' => 'node', 'key' => 'current_other_fees_instance_id' ],
                'premium_bonus_instance_id' => [ 'type' => 'node', 'key' => 'premium_bonus_instance_id' ],
                'interest_bonus_instance_id' => [ 'type' => 'node', 'key' => 'interest_bonus_instance_id' ],
                'interest_multiplier_instance_id' => [ 'type' => 'node', 'key' => 'interest_multiplier_instance_id' ],
                'persistency_credit_instance_id' => [ 'type' => 'node', 'key' => 'persistency_credit_instance_id' ],
                'income_benefit_profile_id' => [ 'type' => 'node', 'key' => 'income_benefit_profile_id' ],
                'income_benefit_current_rider_fees_instance_id' => [ 'type' => 'node', 'key' => 'income_benefit_current_rider_fees_instance_id' ],
                'income_benefit_step_ups_instance_id' => [ 'type' => 'node', 'key' => 'income_benefit_step_ups_instance_id' ],
                'income_benefit_roll_ups_instance_id' => [ 'type' => 'node', 'key' => 'income_benefit_roll_ups_instance_id' ],
                'income_benefit_withdrawal_tables_instance_id' => [ 'type' => 'node', 'key' => 'income_benefit_withdrawal_tables_instance_id' ],
                'income_benefit_withdrawal_ruin_tables_instance_id' => [ 'type' => 'node', 'key' => 'income_benefit_withdrawal_ruin_tables_instance_id' ],
                'income_benefit_premium_bonus_instance_id' => [ 'type' => 'node', 'key' => 'income_benefit_premium_bonus_instance_id' ],
                'income_benefit_premium_multiplier_instance_id' => [ 'type' => 'node', 'key' => 'income_benefit_premium_multiplier_instance_id' ],
                'income_benefit_interest_crediting_instance_id' => [ 'type' => 'node', 'key' => 'income_benefit_interest_crediting_instance_id' ],
                'income_benefit_interest_bonus_crediting_instance_id' => [ 'type' => 'node', 'key' => 'income_benefit_interest_bonus_crediting_instance_id' ],
                'income_benefit_interest_multiplier_crediting_instance_id' => [ 'type' => 'node', 'key' => 'income_benefit_interest_multiplier_crediting_instance_id' ],
                'income_benefit_persistency_credit_instance_id' => [ 'type' => 'node', 'key' => 'income_benefit_persistency_credit_instance_id' ],
                'death_benefit_profile_id' => [ 'type' => 'node', 'key' => 'death_benefit_profile_id' ],
                'death_benefit_current_rider_fees_instance_id' => [ 'type' => 'node', 'key' => 'death_benefit_current_rider_fees_instance_id' ],
                'death_benefit_step_ups_instance_id' => [ 'type' => 'node', 'key' => 'death_benefit_step_ups_instance_id' ],
                'death_benefit_roll_ups_instance_id' => [ 'type' => 'node', 'key' => 'death_benefit_roll_ups_instance_id' ],
                'death_benefit_premium_bonus_instance_id' => [ 'type' => 'node', 'key' => 'death_benefit_premium_bonus_instance_id' ],
                'death_benefit_premium_multiplier_instance_id' => [ 'type' => 'node', 'key' => 'death_benefit_premium_multiplier_instance_id' ],
                'death_benefit_interest_crediting_instance_id' => [ 'type' => 'node', 'key' => 'death_benefit_interest_crediting_instance_id' ],
                'death_benefit_interest_bonus_crediting_instance_id' => [ 'type' => 'node', 'key' => 'death_benefit_interest_bonus_crediting_instance_id' ],
                'death_benefit_interest_multiplier_crediting_instance_id' => [ 'type' => 'node', 'key' => 'death_benefit_interest_multiplier_crediting_instance_id' ],
                'death_benefit_persistency_credit_instance_id' => [ 'type' => 'node', 'key' => 'death_benefit_persistency_credit_instance_id' ],
                'death_benefit_enhancement_instance_id' => [ 'type' => 'node', 'key' => 'death_benefit_enhancement_instance_id' ],
            ]
        ],

         /**
         *
         * Product Instances
         * file: anty_ds_prd_inst-[x].[y].xml
         *
         */
        'anty_ds_prd_inst' => [
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
                            'product_instance_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_instance_id' ],
                            'key' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'key' ],
                            'value' => [ 'type' => 'node', 'key' => '_self' ]
                        ]
                    ],
                    [
                        'data' => [
                            'node' => 'cdsc',
                            'table_name' => 'products_instances_cdsc_schedules',
                            'element' => 'cdsc_schedule_instance'
                        ],
                        'fields' => [
                            'product_instance_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_instance_id' ],
                            'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
                            'years' => [ 'type' => 'attribute', 'key' => 'years', 'data_type' => 'smallInteger' ],
                            'rate' => [ 'type' => 'attribute', 'key' => 'rate', 'data_type' => 'float' ]
                        ]
                    ],
                    [
                        'data' => [
                            'node' => null,
                            'table_name' => 'products_instances_strategies',
                            'element' => 'strategy_details_instance',
                            'child_tables' => [
                                [
                                    'data' => [
                                        'node' => null,
                                        'table_name' => 'products_instances_strategies_rates',
                                        'element' => 'strategy_rate_instance'
                                    ],
                                    'fields' => [
                                        'product_instance_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_instance_id' ],
                                        'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
                                        'premium_range_min' => [ 'type' => 'attribute', 'node' => 'premium_range', 'key' => 'min', 'data_type' => 'float' ],
                                        'premium_range_max' => [ 'type' => 'attribute', 'node' => 'premium_range', 'key' => 'max', 'data_type' => 'float' ],
                                        'current_fixed_rate' => [ 'type' => 'node', 'node' => 'current_fixed_rate', 'data_type' => 'float' ],
                                    ]
                                ],
                                [
                                    'data' => [
                                        'node' => 'tier_rate_frequency',
                                        'table_name' => 'products_instances_strategies_fees_current',
                                        'element' => 'current_strategy_fees_instance'
                                    ],
                                    'fields' => [
                                        'product_instance_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_instance_id' ],
                                        'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
                                        'tier_no' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'tier_no', 'data_type' => 'smallInteger' ],
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
                                        'product_instance_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_instance_id' ],
                                        'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
                                        'tier_no' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'tier_no', 'data_type' => 'smallInteger' ],
                                        'rate' => [ 'type' => 'node', 'key' => 'rate', 'data_type' => 'float' ],
                                        'calculation_frequency' => [ 'type' => 'attribute', 'node' => 'calculation_frequency', 'key' => 'cd' ],
                                    ]
                                ],
                                [
                                    'data' => [
                                        'node' => 'period_range',
                                        'table_name' => 'products_instances_strategies_substrategies',
                                        'element' => 'strategy_range',
                                        'child_tables' => [
                                            [
                                                'data' => [
                                                    'node' => null,
                                                    'table_name' => 'products_instances_strategies_substrategies_ranges',
                                                    'element' => 'strategy_range'
                                                ],
                                                'fields' => [
                                                    'product_instance_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_instance_id' ],
                                                    'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
                                                    'min_indicator' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'min_indicator' ],
                                                    'max_indicator' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'max_indicator' ],
                                                    'min_months' => [ 'type' => 'attribute', 'node' => 'period_range', 'key' => 'min_months', 'data_type' => 'smallInteger' ],
                                                    'min_years' => [ 'type' => 'attribute', 'node' => 'period_range', 'key' => 'min_years', 'data_type' => 'smallInteger' ],
                                                    'max_months' => [ 'type' => 'attribute', 'node' => 'period_range', 'key' => 'max_months', 'data_type' => 'smallInteger' ],
                                                    'max_years' => [ 'type' => 'attribute', 'node' => 'period_range', 'key' => 'max_years', 'data_type' => 'smallInteger' ],
                                                ]
                                            ],
                                            [
                                                'data' => [
                                                    'node' => null,
                                                    'table_name' => 'products_instances_strategies_substrategies_rates',
                                                    'element' => 'strategy_rate_instance'
                                                ],
                                                'fields' => [
                                                    'product_instance_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_instance_id' ],
                                                    'product_strategy_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
                                                    'instance_id' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'instance_id' ],
                                                    'current_participation_rate' => [ 'type' => 'node', 'key' => 'current_participation_rate', 'data_type' => 'float' ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'fields' => [
                                        'product_instance_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_instance_id' ],
                                        'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
                                        'strategy_type' => [ 'type' => 'attribute', 'node' => 'strategy_type', 'key' => 'cd' ],
                                        'strategy_configuration' => [ 'type' => 'attribute', 'node' => 'strategy_configuration', 'key' => 'cd' ],
                                        'index_id' => [ 'type' => 'node', 'key' => 'index_id' ],
                                        'calculation_frequency' => [ 'type' => 'attribute', 'node' => 'calculation_frequency', 'key' => 'cd' ],
                                    ]
                                ],

                            ]
                        ],
                        'fields' => [
                            'product_instance_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_instance_id' ],
                            'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
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
                'product_instance_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_instance_id' ],
                'product_id'  => [ 'type' => 'node', 'key' => 'product_id' ],
                'product_profile_id'  => [ 'type' => 'node', 'key' => 'product_profile_id' ],
                'group_no'  => [ 'type' => 'node', 'key' => 'group_no' ]
            ]
        ],

        /**
         *
         * Product Profile Definitions
         * file: anty_ds_prd_prfl-[x].[y].xml
         *
         */
        'anty_ds_prd_prfl' => [
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
                            'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
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
                            'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
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
                            'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
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
                            'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
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
                            'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
                            'rule_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'rule_id' ],
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
                            'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
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
                            'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
                            'rule_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'rule_id' ],
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
                            'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
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
                            'element' => 'issue_age_joint_owner_rules'
                        ],
                        'fields' => [
                            'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
                            'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
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
                            'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
                            'rule_id' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'rule_id' ],
                            'value' => [ 'type' => 'node', 'key' => '_self', 'data_type' => 'float' ]
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
                            'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
                            'rule_id' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'rule_id' ],
                            'value' => [ 'type' => 'node', 'key' => '_self', 'data_type' => 'float' ]
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
                            'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
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
                            'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
                            'rule_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'rule_id' ],
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
                            'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
                            'rule_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'rule_id' ],
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
                            'instance_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'instance_id' ],
                            'rule_id' => [ 'type' => 'attribute', 'node' => '_parent', 'key' => 'rule_id' ],
                            'name' => [ 'type' => 'node', 'key' => 'name' ],
                            'other_riders_benefits_instance' => [ 'type' => 'attribute', 'node' => 'other_riders_benefits_instance', 'key' => 'cd' ]
                        ]
                    ],
                ]
            ],
            'fields' => [
                'product_profile_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'product_profile_id' ],
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
        ],

        /**
         *
         * Product Rates Definitions
         * file: anty_ds_prd_rate-[x].[y].xml
         *
         * ** DEPRECATED? ** XML FILES BLANK **
         *
         */
        /*
        'anty_ds_prd_rate' => [
            'data' => [
                'node' => ''
                'filename' => 'anty_ds_prd_rate-1.1.xml',
                'table_name' => 'products',
            ],
            'fields' => [

            ]
        ],
        */

        /**
         *
         * Indexes
         * file: anty_ds_index-[x].[y].xml
         *
         */
        'anty_ds_index' => [
            'data' => [
                'node' => 'index',
                'filename' => 'anty_ds_index-1.1.xml',
                'table_name' => 'products'
            ],
            'fields' => [
                'index_id' => [ 'type' => 'attribute', 'node' => '_root', 'key' => 'index_id' ],
                'index_name' => [ 'type' => 'node', 'key' => 'index_name' ],
                'universal_ticker' => [ 'type' => 'node', 'key' => 'universal_ticker' ],
                'live_date' => [ 'type' => 'node', 'key' => 'live_date', 'data_type' => 'date' ],
                'oldest_date' => [ 'type' => 'node', 'key' => 'oldest_date', 'data_type' => 'date' ],
                'most_recent_date' => [ 'type' => 'node', 'key' => 'most_recent_date', 'data_type' => 'date' ],
            ]
        ],

        /**
         *
         * Carriers
         * file: anty_ds_crr_prds-[x].[y].xml
         *
         */
        'anty_ds_crr_prds' => [
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
                            'carrier_id' => [ 'type' => 'toplevel_id', 'data_type' => 'bigInteger' ],
                            'key' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'key' ],
                            'value' => [ 'type' => 'node', 'key' => '_self' ]
                        ]
                    ],
                    [
                        'data' => [
                            'node' => 'carrier_rating',
                            'table_name' => 'carriers_ratings',
                            'element' => null
                        ],
                        'fields' => [
                            'carrier_id' => [ 'type' => 'toplevel_id', 'data_type' => 'bigInteger' ],
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
                                        'carrier_id' => [ 'type' => 'toplevel_id', 'data_type' => 'bigInteger' ],
                                        'key' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'key' ],
                                        'value' => [ 'type' => 'node', 'key' => '_self' ]
                                    ]
                                ]
                            ]
                        ],
                        'fields' => [
                            'carrier_id' => [ 'type' => 'parent_id', 'data_type' => 'bigInteger' ],
                            'product_id' => [ 'type' => 'attribute', 'node' => '_self', 'key' => 'product_id' ],
                            'name' => [ 'type' => 'node', 'key' => 'product_name' ]
                        ]
                    ]
                ]
            ],
            'fields' => [
                'name' => [ 'type' => 'node', 'key' => 'carrier_name' ],
                'version' => [ 'type' => 'node', 'key' => 'version_id' ]
            ]
        ]
    ]
];
