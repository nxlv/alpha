<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsProfilesWithdrawalsFreeRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_profiles_withdrawals_free_rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_profile_id');
            $table->string('instance_id');
            $table->string('rule_id');
            $table->string('tier_no');
            $table->string('rate');
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
        Schema::dropIfExists('products_profiles_withdrawals_free_rates');
    }
}
