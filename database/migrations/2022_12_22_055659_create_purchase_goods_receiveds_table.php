<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseGoodsReceivedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_goods_receiveds', function (Blueprint $table) {
            $table->id();
            $table->string('goods_unique_id');
            $table->integer('purchase_id');
            $table->integer('purchase_product_id');
            $table->integer('required_qty');
            $table->integer('receiving_qty');
            $table->date('received_date');
            $table->integer('created_by');
            $table->integer('status')->default('0');
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
        Schema::dropIfExists('purchase_goods_receiveds');
    }
}
