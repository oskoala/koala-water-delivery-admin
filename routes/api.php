<?php

use App\Http\Services\TicketOrderService;
use App\Models\AppTicketOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get("test", function (Request $request) {
    return Response::json([
        "code" => 0,
        "msg"  => "请求成功",
        "data" => [
            "sentence" => "Hello Laravel !"
        ]
    ]);
});

Route::get("test_get", function (Request $request) {
    return Response::json([
        "code" => 0,
        "msg"  => "请求成功",
        "data" => $request->input()
    ]);
});


Route::post("test_post", function (Request $request) {
    return Response::json([
        "code" => 0,
        "msg"  => "请求成功",
        "data" => $request->input(),
        "d"    => custom_config()
    ]);
});

Route::prefix("v1")->group(function () {
    Route::post("login", [\App\Http\Controllers\Api\UserController::class, "login"]);

    Route::get("ticket_type", [\App\Http\Controllers\Api\TicketController::class, "list"]);
    Route::get("ticket_type/{id}", [\App\Http\Controllers\Api\TicketController::class, "show"]);

    Route::any("ticket_order_pay_notify", [\App\Http\Controllers\Api\TicketOrderController::class, "notify"]);
});

Route::prefix("v1")->middleware(["auth:api"])->group(function () {
    Route::get("info", [\App\Http\Controllers\Api\UserController::class, "info"]);
    Route::post("info", [\App\Http\Controllers\Api\UserController::class, "infoUpdate"]);

    Route::apiResource("address", \App\Http\Controllers\Api\AddressController::class);

    Route::post("create_ticket_order", [\App\Http\Controllers\Api\TicketOrderController::class, 'create']);
    Route::post("show_ticket_order/{order_no}", [\App\Http\Controllers\Api\TicketOrderController::class, "show"]);
    Route::post("pay_ticket_order/{order_no}", [\App\Http\Controllers\Api\TicketOrderController::class, "pay"]);

    Route::post("create_water_order", [\App\Http\Controllers\Api\WaterOrderController::class, "create"]);
    Route::get("water_order_list", [\App\Http\Controllers\Api\WaterOrderController::class, "list"]);
    Route::get("water_order_show/{order_no}", [\App\Http\Controllers\Api\WaterOrderController::class, "show"]);
    Route::post("cancel_water_order/{order_no}", [\App\Http\Controllers\Api\WaterOrderController::class, "cancel"]);

    Route::prefix("delivery")->middleware(["deliverer"])->group(function () {
        Route::post("receipt_water_order/{order_no}", [\App\Http\Controllers\Api\DelivererController::class, "receipt"]);
        Route::post("finish_water_order/{order_no}", [\App\Http\Controllers\Api\DelivererController::class, "finish"]);
        Route::post("water_order_list", [\App\Http\Controllers\Api\DelivererController::class, "list"]);
        Route::post("water_order_statistics", [\App\Http\Controllers\Api\DelivererController::class, "statistics"]);
    });
});

Route::any("test00", function () {
    $service = new TicketOrderService();
    $service->handlePaidTicketOrder(AppTicketOrder::query()->where("no", "20220526544953987272059387")->first());
});
