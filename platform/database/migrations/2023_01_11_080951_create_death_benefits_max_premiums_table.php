<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeathBenefitsMaxPremiumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('death_benefits_max_premiums', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('death_benefit_profile_id');
            $table->string('instance_id');
            $table->string('rule_id');
            $table->string('text_id');
            $table->string('premium_instance_id');
            $table->string('premium_rule_id');
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
        Schema::dropIfExists('death_benefits_max_premiums');
    }
}
