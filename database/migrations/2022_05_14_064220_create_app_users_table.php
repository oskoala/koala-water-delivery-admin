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
        Schema::create('app_users', function (Blueprint $table) {
            $table->id();
            $table->string("openid", 100)->default("")->index()->comment("微信唯一用户识别 OPENID");
            $table->string("nickname", 255)->default("")->comment("昵称");
            $table->string("avatar", 255)->default("")->comment("头像");
            $table->string("mobile", 20)->default("")->unique()->comment("手机号");
            $table->unsignedBigInteger("address_id")->default(0)->comment("默认地址id");
            $table->unsignedTinyInteger("is_write_off_clerk")->default(0)->comment("是不是核销员");
            $table->unsignedTinyInteger("is_disabled")->default(0)->comment("是不是已禁用");
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
        Schema::dropIfExists('app_users');
    }
};
