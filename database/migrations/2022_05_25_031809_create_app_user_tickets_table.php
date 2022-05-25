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
        Schema::create('app_user_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger("user_id")->index()->comment("用户id");
            $table->unsignedTinyInteger("ticket_type_id")->index()->comment("水票类型id");
            $table->integer("num")->default(0)->comment("水票剩余数量");
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
        Schema::dropIfExists('app_user_tickets');
    }
};

// 沐风要搬到楼顶啦
// 说了一下你们俩之间的问题
// 再有就是对你也是很认可
