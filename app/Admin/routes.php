<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'prefix'     => config('admin.route.prefix'),
    'namespace'  => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');


    /**
     * 用户管理
     */
    $router->resource("app-user", "AppUserController");

    /**
     * 水票类型管理
     */
    $router->resource("app-ticket-type", "AppTicketTypeController");

    /**
     * 水票购买订单管理
     */
    $router->resource("app-ticket-order", "AppTicketOrderController");

    /**
     * 预约送水管理
     */
    $router->resource("app-water-order", "AppWaterOrderController");


    /**
     * 用户水票管理
     */
    $router->resource("app-user-ticket", "AppUserTicketController");


    /**
     * 系统设置
     */
    $router->group([
        'prefix' => 'setting'
    ], function ($router) {
        $router->get('system', 'SettingController@front')->name("setting.system");
    });
});
