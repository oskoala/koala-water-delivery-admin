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
        Schema::create('app_water_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->index()->comment("用户id");
            $table->unsignedBigInteger("ticket_type_id")->index()->comment("水票类型id");
            $table->unsignedBigInteger("receipt_user_id")->nullable()->comment("接单人id");
            $table->string("no")->comment("订单号");
            $table->unsignedBigInteger("num")->comment("叫水数量");
            $table->json("address")->comment("地址信息");
            $table->string("status")->comment("状态");
            $table->timestamp("closed_at")->nullable()->comment("关闭时间");
            $table->timestamp("receipt_at")->nullable()->comment("接单时间");
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
        Schema::dropIfExists('app_water_orders');
    }
};
