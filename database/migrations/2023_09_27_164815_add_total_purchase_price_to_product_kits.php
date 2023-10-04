<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalPurchasePriceToProductKits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_kits', function (Blueprint $table) {
            $table->string('total_purchase_price')->after('product_id')->nullable();
            $table->string('total_cost_price')->after('created_by')->nullable();
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
            $table->dropColumn('total_purchase_price');
            $table->dropColumn('total_cost_price');
        });
    }
}
