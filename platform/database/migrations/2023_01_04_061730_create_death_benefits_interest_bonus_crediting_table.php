<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeathBenefitsInterestBonusCreditingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('death_benefits_interest_bonus_crediting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('death_benefit_profile_id');
            $table->string('instance_id');
            $table->string('rule_id');
            $table->smallInteger('tier_no');
            $table->float('rate');
            $table->string('frequency_inheritance');
            $table->string('period_months');
            $table->string('period_years');
            $table->string('calculation_frequency');
            $table->string('crediting_frequency');
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
        Schema::dropIfExists('death_benefits_interest_bonus_crediting');
    }
}
