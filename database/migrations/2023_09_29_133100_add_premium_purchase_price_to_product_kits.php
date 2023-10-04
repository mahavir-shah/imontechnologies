<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPremiumPurchasePriceToProductKits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_kits', function (Blueprint $table) {
            $table->string('total_premium_purchase_price')->after('total_standard_purchase_price')->nullable();
            $table->string('total_alliance_purchase_price')->after('product_info')->nullable();
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
