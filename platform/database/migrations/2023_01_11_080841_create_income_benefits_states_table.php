<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomeBenefitsStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_benefits_states', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('income_benefit_profile_id');
            $table->string('list_cd');
            $table->string('state_cd');
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
        Schema::dropIfExists('income_benefits_states');
    }
}
