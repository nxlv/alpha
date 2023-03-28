<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analysis_cache', function (Blueprint $table) {
            $table->id();
            $table->string('analysis_data_id');
            $table->smallInteger('deferral');
            $table->decimal('premium', 12, 4);
            $table->decimal('income', 12, 4);
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
        Schema::dropIfExists('analysis_cache');
    }
};
