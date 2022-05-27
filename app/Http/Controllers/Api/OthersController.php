<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Jiannei\Response\Laravel\Support\Facades\Response;

class OthersController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     * 系统设置
     */
    public function systemConfig(Request $request)
    {
        if (Cache::has("system_config")) {
            return Response::success(Cache::get("system_config", []));
        } else {
            $system_config = custom_config();
            Cache::put("system_config", $system_config);
            return Response::success($system_config);
        }
    }
}
