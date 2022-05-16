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
        Schema::create('app_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->index()->comment("用户id");
            $table->string("name", "100")->default("")->comment("收货人姓名");
            $table->string("phone", "20")->default("")->comment("手机号");
            $table->string("village", "50")->default("")->comment("小区");
            $table->string("building", "50")->default("")->comment("楼号");
            $table->string("unit", "50")->default("")->comment("单元");
            $table->string("room", "50")->default("")->comment("房间号");
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
        Schema::dropIfExists('app_addresses');
    }
};
