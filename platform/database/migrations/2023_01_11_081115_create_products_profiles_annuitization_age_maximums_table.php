<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsProfilesAnnuitizationAgeMaximumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_profiles_annuitization_age_maximums', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_profile_id');
            $table->string('instance_id');
            $table->string('rule_id');
            $table->smallInteger('years');
            $table->smallInteger('months');
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
        Schema::dropIfExists('products_profiles_annuitization_age_maximums');
    }
}
