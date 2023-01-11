<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsProfilesIssueAgeAnnuitantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_profiles_issue_age_annuitant', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_profile_id');
            $table->string('instance_id');
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
        Schema::dropIfExists('products_profiles_issue_age_annuitant');
    }
}
