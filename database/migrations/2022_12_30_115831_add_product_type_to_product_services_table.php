<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductTypeToProductServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_services', function (Blueprint $table) {
            $table->string('product_type');
            $table->string('hsn_code');
            $table->integer('import_cost')->default(0);
            $table->integer('msp_margin')->default(0);
            $table->integer('lsp_margin')->default(0);
            $table->string('type')->default('product')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_services', function (Blueprint $table) {
            //
        });
    }
}
