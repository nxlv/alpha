<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsInstancesStrategiesRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_instances_strategies_rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_instance_id');
            $table->string('product_strategy_instance_id');
            $table->string('instance_id');
            $table->decimal('premium_range_min', 12, 4);
            $table->decimal('premium_range_max', 12, 4);
            $table->float('current_fixed_rate');
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
        Schema::dropIfExists('products_instances_strategies_rates');
    }
}
