<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseBarcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_barcodes', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_id');
            $table->integer('product_id');
            $table->integer('purchase_product_id');
            $table->string('sr_no')->default('null');
            $table->string('barcode');
            $table->integer('status')->default('0');
            $table->integer('created_by')->default('0');
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
        Schema::dropIfExists('purchase_barcodes');
    }
}
