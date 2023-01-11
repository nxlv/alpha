<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeathBenefitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('death_benefits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('death_benefit_profile_id');
            $table->string('name');
            $table->date('date_open');
            $table->date('date_closed');
            $table->string('death_benefit_type');
            $table->string('death_benefit_proprietary_type');
            $table->string('age_rule_basis');
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
            $table->string('death_benefit_enhancement_text_id');
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
        Schema::dropIfExists('death_benefits');
    }
}
