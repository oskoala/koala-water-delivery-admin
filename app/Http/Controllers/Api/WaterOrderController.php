<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\WaterOrderRequest;
use App\Models\AppWaterOrder;
use App\Status\WaterOrderStatus;
use Illuminate\Http\Request;

class WaterOrderController
{
    /**
     * @param WaterOrderRequest $request
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * 创建预约叫水订单
     */
    public function create(WaterOrderRequest $request)
    {
        $request->validate();
        $params            = $request->input();
        $params['user_id'] = auth()->id();
        $params['no']      = get_unique_no();
        $params['status']  = WaterOrderStatus::create;
        $order             = AppWaterOrder::query()->create($params);
        return \Response::success($order);
    }

    /**
     * @param Request $request
     * 预约记录列表
     */
    public function list(Request $request)
    {
        $params = $request->input();

        $builder = AppWaterOrder::query();
        if (isset($params['status']) && $params['status']) {
            $builder->where("status", $params['status']);
        }

        $page_size = $params['page_size'] ?? 10;
        $items     = $builder->paginate($page_size);

        return \Response::success($items);
    }
}
