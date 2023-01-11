<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeathBenefitsStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('death_benefits_states', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('death_benefit_profile_id');
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
        Schema::dropIfExists('death_benefits_states');
    }
}
