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
        Schema::create('app_ticket_package_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->index()->comment("用户id");
            $table->unsignedBigInteger("ticket_package_id")->index()->comment("水票包id");
            $table->string("no")->index()->comment("单号");
            $table->string("transaction_id")->default("")->comment("微信交易编号");
            $table->decimal("total_price", 10, 2)->comment("订单金额");
            $table->string("status")->comment("订单状态");
            $table->json("snapshot")->nullable()->comment("快照数据");
            $table->timestamp("paid_at")->nullable()->comment("支付时间");
            $table->timestamp("closed_at")->nullable()->comment("订单关闭时间");
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
        Schema::dropIfExists('app_ticket_package_orders');
    }
};
