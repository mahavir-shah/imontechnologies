<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('website')->nullable();
            $table->string('gst')->nullable();
            $table->string('pan')->nullable();
            $table->string('dealer_category')->nullable();
            $table->string('discount');
            $table->string('credit_day')->nullable();
            $table->string('credit_limit')->nullable();
            $table->bigInteger('bank_account_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_ifsc_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
}
