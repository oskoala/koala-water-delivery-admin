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
     * 系统设置
     */
    $router->group([
        'prefix' => 'setting'
    ], function ($router) {
        $router->get('system', 'SettingController@front')->name("setting.system");
    });
});
