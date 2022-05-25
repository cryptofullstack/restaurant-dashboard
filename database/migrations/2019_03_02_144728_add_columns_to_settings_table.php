<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('minimal_delivery_time');

            $table->boolean('mon_opened')->default(true);
            $table->string('mon_open')->nullable();
            $table->string('mon_close')->nullable();

            $table->boolean('tue_opened')->default(true);
            $table->string('tue_open')->nullable();
            $table->string('tue_close')->nullable();

            $table->boolean('wed_opened')->default(true);
            $table->string('wed_open')->nullable();
            $table->string('wed_close')->nullable();

            $table->boolean('thu_opened')->default(true);
            $table->string('thu_open')->nullable();
            $table->string('thu_close')->nullable();

            $table->boolean('fri_opened')->default(true);
            $table->string('fri_open')->nullable();
            $table->string('fri_close')->nullable();

            $table->boolean('sat_opened')->default(true);
            $table->string('sat_open')->nullable();
            $table->string('sat_close')->nullable();

            $table->boolean('sun_opened')->default(true);
            $table->string('sun_open')->nullable();
            $table->string('sun_close')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('minimal_delivery_time');
            $table->dropColumn('mon_open');
            $table->dropColumn('mon_close');
            $table->dropColumn('tue_open');
            $table->dropColumn('tue_close');
            $table->dropColumn('wed_open');
            $table->dropColumn('wed_close');
            $table->dropColumn('thu_open');
            $table->dropColumn('thu_close');
            $table->dropColumn('fri_open');
            $table->dropColumn('fri_close');
            $table->dropColumn('sat_open');
            $table->dropColumn('sat_close');
            $table->dropColumn('sun_open');
            $table->dropColumn('sun_close');
        });
    }
}
