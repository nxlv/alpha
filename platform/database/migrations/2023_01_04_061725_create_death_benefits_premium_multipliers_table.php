<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeathBenefitsPremiumMultipliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('death_benefits_premium_multipliers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('death_benefit_profile_id');
            $table->string('instance_id');
            $table->string('rule_id');
            $table->string('text_id');
            $table->smallInteger('tier_no');
            $table->float('rate');
            $table->string('months');
            $table->string('years');
            $table->string('frequency_inheritance');
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
        Schema::dropIfExists('death_benefits_premium_multipliers');
    }
}
