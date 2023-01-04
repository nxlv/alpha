<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRulesGuaranteePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rules_guarantee_periods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('rule_id');
            $table->smallInteger('min_years');
            $table->smallInteger('min_months');
            $table->smallInteger('max_years');
            $table->smallInteger('max_months');
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
        Schema::dropIfExists('rules_guarantee_periods');
    }
}
