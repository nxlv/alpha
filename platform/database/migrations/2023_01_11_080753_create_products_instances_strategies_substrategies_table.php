<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsInstancesStrategiesSubstrategiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_instances_strategies_substrategies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_instance_id');
            $table->string('product_strategy_instance_id');
            $table->string('instance_id');
            $table->string('strategy_type');
            $table->string('strategy_configuration');
            $table->string('index_id');
            $table->string('calculation_frequency');
            $table->string('strategy_range_indicator_min');
            $table->string('strategy_range_indicator_max');
            $table->smallInteger('strategy_range_period_years_min');
            $table->smallInteger('strategy_range_period_years_max');
            $table->smallInteger('strategy_range_period_months_min');
            $table->smallInteger('strategy_range_period_months_max');
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
        Schema::dropIfExists('products_instances_strategies_substrategies');
    }
}
