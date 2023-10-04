<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosShipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_ships', function (Blueprint $table) {
            $table->id();
            $table->integer('pos_id');
            $table->string('ship_unique',15);
            $table->integer('customer_id');
            $table->string('total_amt',10);
            $table->string('carton')->nullable();
            $table->string('status', 30)->default('pending');
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
        Schema::dropIfExists('pos_ships');
    }
}
