<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsInstancesStrategiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_instances_strategies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_instance_id');
            $table->string('instance_id');
            $table->string('strategy_type');
            $table->string('strategy_configuration');
            $table->string('guarantee_status');
            $table->string('calculation_frequency');
            $table->string('crediting_frequency');
            $table->smallInteger('guarantee_period_years');
            $table->smallInteger('guarantee_period_months');
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
        Schema::dropIfExists('products_instances_strategies');
    }
}
