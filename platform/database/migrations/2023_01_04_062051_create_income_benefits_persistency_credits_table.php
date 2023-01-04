<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomeBenefitsPersistencyCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_benefits_persistency_credits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('income_benefit_profile_id');
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
        Schema::dropIfExists('income_benefits_persistency_credits');
    }
}
