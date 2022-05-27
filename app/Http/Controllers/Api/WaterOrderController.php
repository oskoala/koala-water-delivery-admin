<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\WaterOrderRequest;
use App\Http\Services\WaterOrderService;
use App\Models\AppWaterOrder;
use Illuminate\Http\Request;
use Jiannei\Response\Laravel\Support\Facades\Response;

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
        $service = new WaterOrderService();
        $order   = $service->create($request);
        return Response::success($order);
    }

    /**
     * @param Request $request
     * 预约记录列表
     */
    public function list(Request $request)
    {
        $params = $request->input();

        $builder = AppWaterOrder::query();
        $builder->with([
            "ticket_type:id,name,image,price,min_buy_num",
            "receipt_user",
        ]);

        // 筛选不同状态
        if (isset($params['status']) && $params['status']) {
            $builder->where("status", $params['status']);
        }

        $builder->orderByDesc("created_at");

        $page_size = $params['page_size'] ?? 10;
        $items     = $builder->paginate($page_size);

        return Response::success($items);
    }

    /**
     * @param $order_no
     * @return mixed
     * 详情
     */
    public function show($order_no)
    {
        $item = AppWaterOrder::query()->with([
            "ticket_type:id,name,image,price,min_buy_num",
            "receipt_user",
        ])->where("no", $order_no)->first();
        return Response::success($item);
    }

    /**
     * @param $order_no
     * @return mixed
     * 取消预约
     */
    public function cancel($order_no)
    {
        $service = new WaterOrderService();
        $service->cancel($order_no);
        return Response::success();
    }
}
