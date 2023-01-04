<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsInstancesStrategiesFeesMaximumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_instances_strategies_fees_maximum', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_instance_id');
            $table->string('product_strategy_instance_id');
            $table->string('instance_id');
            $table->smallInteger('tier_no');
            $table->float('rate');
            $table->string('calculation_frequency');
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
        Schema::dropIfExists('products_instances_strategies_fees_maximum');
    }
}
