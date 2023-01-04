<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('analysis_cd');
            $table->string('analysis_data_id');
            $table->string('rule_id');
            $table->string('product_id');
            $table->string('product_profile_id');
            $table->string('product_instance_id');
            $table->string('strategy_details_instance_id');
            $table->string('strategy_rate_instance_id');
            $table->string('minimum_rate_instance_id');
            $table->smallInteger('guarantee_period_months');
            $table->smallInteger('guarantee_period_years');
            $table->smallInteger('surrender_period_months');
            $table->smallInteger('surrender_period_years');
            $table->string('cdsc_schedule_instance_id');
            $table->string('free_withdrawals_instance_id');
            $table->string('standard_gmv_initial_rate_instance_id');
            $table->string('standard_gmv_increase_rate_instance_id');
            $table->string('enhanced_gmv_initial_rate_instance_id');
            $table->string('enhanced_gmv_increase_rate_instance_id');
            $table->string('current_m_e_fees_instance_id');
            $table->string('current_admin_fees_instance_id');
            $table->string('current_fund_facilitation_fees_instance_id');
            $table->string('current_premium_based_fees_instance_id');
            $table->string('current_other_fees_instance_id');
            $table->string('premium_bonus_instance_id');
            $table->string('interest_bonus_instance_id');
            $table->string('interest_multiplier_instance_id');
            $table->string('persistency_credit_instance_id');
            $table->string('income_benefit_profile_id');
            $table->string('income_benefit_current_rider_fees_instance_id');
            $table->string('income_benefit_step_ups_instance_id');
            $table->string('income_benefit_roll_ups_instance_id');
            $table->string('income_benefit_withdrawal_tables_instance_id');
            $table->string('income_benefit_withdrawal_ruin_tables_instance_id');
            $table->string('income_benefit_premium_bonus_instance_id');
            $table->string('income_benefit_premium_multiplier_instance_id');
            $table->string('income_benefit_interest_crediting_instance_id');
            $table->string('income_benefit_interest_bonus_crediting_instance_id');
            $table->string('income_benefit_interest_multiplier_crediting_instance_id');
            $table->string('income_benefit_persistency_credit_instance_id');
            $table->string('death_benefit_profile_id');
            $table->string('death_benefit_current_rider_fees_instance_id');
            $table->string('death_benefit_step_ups_instance_id');
            $table->string('death_benefit_roll_ups_instance_id');
            $table->string('death_benefit_premium_bonus_instance_id');
            $table->string('death_benefit_premium_multiplier_instance_id');
            $table->string('death_benefit_interest_crediting_instance_id');
            $table->string('death_benefit_interest_bonus_crediting_instance_id');
            $table->string('death_benefit_interest_multiplier_crediting_instance_id');
            $table->string('death_benefit_persistency_credit_instance_id');
            $table->string('death_benefit_enhancement_instance_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
