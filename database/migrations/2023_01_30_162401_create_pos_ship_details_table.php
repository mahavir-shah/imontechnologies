<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosShipDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_ship_details', function (Blueprint $table) {
            $table->id();
            $table->integer('pos_id');
            $table->integer('pos_ship_id');
            $table->integer('product_total');
            $table->string('carton',25);
            $table->string('delivery',25);
            $table->string('carrier_type',25)->nullable();
            $table->string('carrier_name')->nullable();
            $table->integer('tracking_number')->default(0);
            $table->integer('width')->default(0);
            $table->integer('weight')->default(0);
            $table->integer('height')->default(0);
            $table->string('length',25)->nullable();
            $table->string('box_type',25)->nullable();
            $table->string('status')->default('shipped');
            $table->integer('created_by');
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
        Schema::dropIfExists('pos_ship_details');
    }
}
