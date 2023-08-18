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
        Schema::create('analysis_guaranteed_cache', function (Blueprint $table) {
            $table->id();
            $table->string('analysis_data_id');
            $table->smallInteger('deferral');
            $table->decimal('premium', 12, 4);
            $table->string( 'owner_state' );
            $table->boolean( 'is_estimated_return' )->default( false );
            $table->boolean( 'is_joint' );
            $table->decimal('income_initial', 12, 4);
            $table->decimal('income_high', 12, 4);
            $table->decimal('income_low', 12, 4);
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
        Schema::dropIfExists('analysis_guaranteed_cache');
    }
};
