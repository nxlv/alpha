<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsProfilesIssueAgeJointOwnerRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_profiles_issue_age_joint_owner_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_profile_id');
            $table->string('min_age_basis');
            $table->string('max_age_basis');
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
        Schema::dropIfExists('products_profiles_issue_age_joint_owner_rules');
    }
}
