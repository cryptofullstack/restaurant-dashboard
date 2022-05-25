<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('store_name');
            $table->string('business_name');
            $table->string('phone');
            $table->string('deliver_cost');
            $table->string('address');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('home_header');
            $table->string('info_header');
            $table->string('open_time');
            $table->string('close_time');
            $table->boolean('is_open_sat')->default(false);
            $table->string('sat_open_time')->nullable();
            $table->string('sat_close_time')->nullable();
            $table->boolean('is_open_sun')->default(false);
            $table->string('sun_open_time')->nullable();
            $table->string('sun_close_time')->nullable();
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
