<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosPackingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_packing_details', function (Blueprint $table) {
            $table->id();
            $table->integer('pos_id');
            $table->integer('ship_id');
            $table->integer('picking_id')->default(0);
            $table->integer('product_id');
            $table->integer('carton');
            $table->integer('qty');
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
        Schema::dropIfExists('pos_packing_details');
    }
}
