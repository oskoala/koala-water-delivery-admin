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
        Schema::create('app_ticket_packages', function (Blueprint $table) {
            $table->id();
            $table->string("title", 100)->comment("名称");
            $table->string("image", 100)->comment("图片");
            $table->longText("detail")->comment("详细介绍");
            $table->unsignedBigInteger("ticket_type_id")->index()->comment("那种水票");
            $table->unsignedBigInteger("num")->comment("水票数量");
            $table->decimal("price", 10, 2)->comment("价格");
            $table->decimal("scribing_price", 10, 2)->comment("划线价");
            $table->tinyInteger("show")->default(true)->comment("是否显示");
            $table->integer("order")->default(99)->comment("序号越小越靠前");
            $table->tinyInteger("recommend")->default(false)->comment("是否推荐");
            $table->softDeletes();
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
        Schema::dropIfExists('app_ticket_packages');
    }
};
