<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameProductIdInPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('product_kits', function (Blueprint $table) {
            $table->renameColumn('product_id', 'product_info');
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
            $table->renameColumn('product_info', 'product_id');
        });
    }
}
