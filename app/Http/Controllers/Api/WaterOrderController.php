<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\WaterOrderRequest;
use App\Models\AppAddress;
use App\Models\AppTicketType;
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
        $params['status']  = WaterOrderStatus::created;
        if (isset($params['address_id']) && $params['address_id']) {
            $params['address'] = AppAddress::query()->find($params['address_id']);
        }
        $order = AppWaterOrder::query()->create($params);
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

        return \Response::success($items);
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
        return \Response::success($item);
    }

    /**
     * @param $order_no
     * @return mixed
     * 取消预约
     */
    public function cancel($order_no)
    {
        $item = AppWaterOrder::query()->where("no", $order_no)->first();
        if (!$item) {
            abort(422, "预约不存在");
        }
        if ($item->status == WaterOrderStatus::finished) {
            abort(422, "已完成订单无法取消");
        } else if ($item->status == WaterOrderStatus::received) {
            abort(422, "配送员已结单无法取消");
        } else if ($item->status == WaterOrderStatus::canceled) {
            abort(422, "订单已取消");
        }
        $item->closed_at = now();
        $item->status    = WaterOrderStatus::canceled;
        $item->save();
        return \Response::success();
    }
}
