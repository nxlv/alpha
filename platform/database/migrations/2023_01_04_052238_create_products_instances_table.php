<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_instances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_instance_id');
            $table->string('product_id');
            $table->string('product_profile_id');
            $table->string('group_no');
            $table->date('rate_effective_date');
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
        Schema::dropIfExists('products_instances');
    }
}
