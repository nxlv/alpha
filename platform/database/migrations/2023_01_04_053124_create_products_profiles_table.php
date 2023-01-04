<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_profile_id');
            $table->string('product_id');
            $table->string('surrender_period_basis');
            $table->string('minimum_withdrawals_rule_applicable');
            $table->string('systematic_withdrawals_available');
            $table->string('bail_out');
            $table->string('free_look_period');
            $table->string('fees_commissions');
            $table->string('contract_continuation');
            $table->string('age_rule_basis');
            $table->string('issue_age_joint_availability');
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
        Schema::dropIfExists('products_profiles');
    }
}
