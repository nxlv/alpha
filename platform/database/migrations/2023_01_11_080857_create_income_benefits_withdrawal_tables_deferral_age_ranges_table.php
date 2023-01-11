<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomeBenefitsWithdrawalTablesDeferralAgeRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_benefits_withdrawal_tables_deferral_age_ranges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('income_benefit_profile_id');
            $table->bigInteger('tier_id');
            $table->smallInteger('min_years');
            $table->smallInteger('min_months');
            $table->smallInteger('max_years');
            $table->smallInteger('max_months');
            $table->float('rate');
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
        Schema::dropIfExists('income_benefits_withdrawal_tables_deferral_age_ranges');
    }
}
