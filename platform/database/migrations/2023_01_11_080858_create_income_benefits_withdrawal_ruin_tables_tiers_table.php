<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomeBenefitsWithdrawalRuinTablesTiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_benefits_withdrawal_ruin_tables_tiers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('income_benefit_profile_id');
            $table->string('instance_id');
            $table->string('rule_id');
            $table->smallInteger('tier_no');
            $table->string('withdrawal_table_type');
            $table->string('multi_year_income_deferral_table');
            $table->smallInteger('deferral_years');
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
        Schema::dropIfExists('income_benefits_withdrawal_ruin_tables_tiers');
    }
}
