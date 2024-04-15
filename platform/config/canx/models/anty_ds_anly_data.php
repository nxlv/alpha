<?php

return [
    /**
     *
     * Product List
     * file: anty_ds_anly_data-[x].[y].xml
     *
     */
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
                    'analysis_data_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'analysis_data_id' ],
                    'key' => [ 'type' => 'attribute', 'target' => '_self', 'key' => 'key' ],
                    'value' => [ 'type' => 'node', 'key' => '_self' ]
                ]
            ]
        ]
    ],
    'fields' => [
        'analysis_cd' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'analysis_cd' ],
        'analysis_data_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'analysis_data_id' ],
        'rule_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'rule_id' ],
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
];
