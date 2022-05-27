<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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
            $table->string("province", "100")->default("")->comment("省");
            $table->string("city", "100")->default("")->comment("市");
            $table->string("district", "100")->default("")->comment("区");
            $table->string("detail", "100")->default("")->comment("详细地址");
            $table->unsignedTinyInteger("is_default")->default(0)->comment("是否是默认地址");
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
