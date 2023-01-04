<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomeBenefitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_benefits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('income_benefit_profile_id');
            $table->string('name');
            $table->date('date_open');
            $table->date('date_closed');
            $table->string('income_benefit_type');
            $table->string('income_benefit_proprietary_type');
            $table->string('income_start_age');
            $table->string('income_start_age_basis');
            $table->string('issue_age_inheritance');
            $table->string('issue_age_joint_availability');
            $table->string('issue_age_joint_owner_rules_min_age');
            $table->string('issue_age_joint_owner_rules_max_age');
            $table->string('issue_age_joint_annuitant_rules_min_age');
            $table->string('issue_age_joint_annuitant_rules_max_age');
            $table->string('issue_age_notice_text_id');
            $table->string('rider_fees_notice_text_id');
            $table->string('step_ups_notice_text_id');
            $table->string('premium_inheritance');
            $table->string('premium_notice_text_id');
            $table->string('premium_bonus_text_id');
            $table->string('premium_multiplier_text_id');
            $table->string('interest_crediting_text_id');
            $table->string('interest_bonus_crediting_text_id');
            $table->string('interest_multiplier_crediting_text_id');
            $table->string('persistency_credit_text_id');
            $table->string('income_start_age_text_id');
            $table->string('withdrawal_rates_text_id');
            $table->string('withdrawal_ruin_rates_text_id');
            $table->string('rmd_friendly');
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
        Schema::dropIfExists('income_benefits');
    }
}
