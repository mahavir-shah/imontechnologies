<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_id');
            $table->integer('pos_id');
            $table->integer('pos_product_id');
            $table->integer('required_qty');
            $table->integer('returned_qty');
            $table->integer('return_qty');
            $table->date('return_date');
            $table->integer('status')->default('0');
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
        Schema::dropIfExists('pos_returns');
    }
}
