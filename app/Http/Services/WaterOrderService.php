<?php


namespace App\Http\Services;


use App\Http\Requests\WaterOrderRequest;
use App\Models\AppAddress;
use App\Models\AppUserTicket;
use App\Models\AppWaterOrder;
use App\Status\WaterOrderStatus;

class WaterOrderService
{
    /**
     * @param WaterOrderRequest $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * 创建叫水订单
     */
    public function create(WaterOrderRequest $request)
    {
        $params            = $request->input();
        $user_id           = auth()->id();
        $params['user_id'] = $user_id;
        $params['no']      = get_unique_no();
        $params['status']  = WaterOrderStatus::created;
        if (isset($params['address_id']) && $params['address_id']) {
            $params['address'] = AppAddress::query()->find($params['address_id']);
        }
        // 开启事务
        \DB::beginTransaction();
        try {
            $order       = AppWaterOrder::query()->create($params);
            $user_ticket = AppUserTicket::query()->where("user_id", $user_id)->where("ticket_type_id", $params['ticket_type_id'])->lockForUpdate()->first();
            if (!$user_ticket || $user_ticket->num < $params['num']) {
                abort(422, "可用水票数量不足");
            }

            if ($params['address']->lat && $params['address']->lon) {
                $addressLat = $params['address']->lat;
                $addressLon = $params['address']->lon;
                $shopLat    = custom_config("shop_lat");
                $shopLon    = custom_config("shop_lon");
                $distance   = cal_distance($addressLat, $addressLon, $shopLat, $shopLon);
                if ($distance > custom_config("deliver_scope")) {
                    abort(422, "超出配送范围：" . custom_config("deliver_scope") . "公里");
                }
            }

            $user_ticket->update([
                "num" => $user_ticket->num - $params['num']
            ]);

            // 没有错误确认提交修改
            \DB::commit();
            return $order;
        } catch (\Exception $exception) {
            // 发生错误数据库回滚，撤销所有操作
            \DB::rollBack();
            abort(422, $exception->getMessage());
        }
    }

    /**
     * @param $order_no
     * 取消叫水订单
     */
    public function cancel($order_no)
    {
        \DB::beginTransaction();
        try {
            $item = AppWaterOrder::query()->where("no", $order_no)->lockForUpdate()->first();
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

            // 修改预约订单信息为已撤销
            $item->closed_at = now();
            $item->status    = WaterOrderStatus::canceled;
            $item->save();

            // 返还使用水票
            $ticket_type_id = $item->ticket_type_id;
            $user_id        = auth()->id();
            $user_ticket    = AppUserTicket::query()->where("user_id", $user_id)->where("ticket_type_id", $ticket_type_id)->first();

            $user_ticket->update([
                "num" => $user_ticket->num + $item->num,
            ]);

            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            abort(422, $exception->getMessage());
        }
    }
}
