<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('order_city');
            $table->string('order_street');
            $table->string('order_zip');
            $table->string('order_username');
            $table->string('order_email');
            $table->string('order_phone');
            $table->string('order_bname')->nullable();
            $table->boolean('is_infom')->default(false);
            $table->string('order_time');
            $table->string('total_price');
            $table->integer('payment_method');
            $table->string('payment_id')->nullable();
            $table->boolean('is_payed')->default(false);
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
        Schema::dropIfExists('orders');
    }
}
