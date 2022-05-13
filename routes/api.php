<?php

use Illuminate\Http\Request;
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
