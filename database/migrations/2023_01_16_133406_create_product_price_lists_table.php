<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPriceListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_price_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('name');
            $table->string('sku');
            $table->text('description')->nullable();
            $table->float('purchase_price',20)->default('0.0');
            $table->float('import_cost_per',20);
            $table->float('msp_margin_per',20);
            $table->float('lsp_margin_per',20);
            $table->float('alliance_per',20);
            $table->float('premium_per',20);
            $table->float('standard_per',20);
            $table->float('import_cost',20);
            $table->float('msp_margin',20);
            $table->float('lsp_margin',20);
            $table->float('alliance',20);
            $table->float('premium',20);
            $table->float('standard',20);
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
        Schema::dropIfExists('product_price_lists');
    }
}
