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
        Schema::table('analysis_guaranteed_cache', function (Blueprint $table) {
            $table->smallInteger( 'purchase_age' )->nullable( true )->default( null )->after( 'owner_state' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('analysis_guaranteed_cache', function (Blueprint $table) {
            $table->dropColumn( 'purchase_age' );
        });
    }
};
