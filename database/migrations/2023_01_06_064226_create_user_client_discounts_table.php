<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserClientDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_client_discounts', function (Blueprint $table) {
            $table->id();
            $table->string('discount');
            $table->string('client_type');
            $table->string('payment');
            $table->string('transaction_limit');
            $table->string('tread_discount')->nullable();
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
        Schema::dropIfExists('user_client_discounts');
    }
}
