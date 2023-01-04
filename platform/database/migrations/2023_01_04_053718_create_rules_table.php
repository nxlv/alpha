<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('rule_id');
            $table->string('contract');
            $table->string('gender');
            $table->decimal('premium_min', 12, 4);
            $table->decimal('premium_max', 12, 4);
            $table->smallInteger('age_range_min_years');
            $table->smallInteger('age_range_min_months');
            $table->smallInteger('age_range_max_years');
            $table->smallInteger('age_range_max_months');
            $table->string('mva_cd');
            $table->string('rop_cd');
            $table->string('strategy_fees');
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
        Schema::dropIfExists('rules');
    }
}
