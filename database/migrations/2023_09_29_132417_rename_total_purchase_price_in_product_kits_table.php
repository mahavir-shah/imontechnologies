<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTotalPurchasePriceInProductKitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_kits', function (Blueprint $table) {
            $table->renameColumn('total_purchase_price', 'total_standard_purchase_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_kits', function (Blueprint $table) {
            //
        });
    }
}
