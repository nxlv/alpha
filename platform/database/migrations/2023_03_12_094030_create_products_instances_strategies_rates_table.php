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
            $table->float('current_increase_fixed_rate');
            $table->float('current_declared_rate');
            $table->float('current_cap_rate');
            $table->string('current_cap_rate_cd');
            $table->float('current_spread_rate');
            $table->float('current_participation_rate');
            $table->float('current_protection_buffer_rate');
            $table->float('current_protection_floor_rate');
            $table->float('current_protection_downside_participation_rate');
            $table->float('announced_fixed_rate');
            $table->float('announced_declared_rate');
            $table->float('announced_cap_rate');
            $table->string('announced_cap_rate_cd');
            $table->float('announced_spread_rate');
            $table->float('announced_participation_rate');
            $table->float('announced_protection_buffer_rate');
            $table->float('announced_protection_floor_rate');
            $table->float('announced_protection_downside_participation_rate');
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
