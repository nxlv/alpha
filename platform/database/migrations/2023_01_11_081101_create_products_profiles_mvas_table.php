<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsProfilesMvasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_profiles_mvas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_profile_id');
            $table->string('instance_id');
            $table->string('rule_id');
            $table->string('code');
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
        Schema::dropIfExists('products_profiles_mvas');
    }
}
