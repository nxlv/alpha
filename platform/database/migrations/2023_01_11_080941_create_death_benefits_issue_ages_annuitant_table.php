<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeathBenefitsIssueAgesAnnuitantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('death_benefits_issue_ages_annuitant', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('death_benefit_profile_id');
            $table->string('instance_id');
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
        Schema::dropIfExists('death_benefits_issue_ages_annuitant');
    }
}
