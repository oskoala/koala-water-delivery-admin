<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_addresses', function (Blueprint $table) {
            $table->double("lat")->after("detail")->nullable()->comment("维度");
            $table->double("lon")->after("detail")->nullable()->comment("经度");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_addresses', function (Blueprint $table) {
            $table->dropColumn([
                'lat',
                'lon',
            ]);
        });
    }
};
