<?php


namespace App\Http\Controllers\Api;


use App\Models\AppWaterOrder;
use App\Status\WaterOrderStatus;
use Illuminate\Http\Request;
use Jiannei\Response\Laravel\Support\Facades\Response;

class DelivererController
{
    /**
     * @param $status
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     * 状态查询构建
     */
    private function getStatusBuilder($status, \Illuminate\Database\Eloquent\Builder $builder)
    {
        if ($status) {
            //待结单订单
            if ($status == WaterOrderStatus::created) {
                $builder->where("status", WaterOrderStatus::created);
            } else {
                // 除了待结单之外的订单，都要查看自己已接单的
                $builder->where("receipt_user_id", auth()->id());

                if ($status == "unfinished") {
                    $builder->where("status", "!=", WaterOrderStatus::finished);
                } else {
                    $builder->where("status", $status);
                }
            }
        } else {
            $builder->where("receipt_user_id", auth()->id());
        }
        return $builder;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     * 配送员订单列表
     */
    public function list(Request $request)
    {
        $params = $request->input();

        $builder = AppWaterOrder::query();

        $status = $params['status'] ?? "";

        $this->getStatusBuilder($status, $builder);

        $builder->orderByDesc("created_at");

        $page_size = $request->input("page_size", 10);
        return Response::success(
            $builder->paginate($page_size)
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     * 数量统计
     */
    public function statistics()
    {
        return Response::success([
            "unfinished"              => $this->getStatusBuilder("unfinished", AppWaterOrder::query())->count(),
            WaterOrderStatus::created => $this->getStatusBuilder(WaterOrderStatus::created, AppWaterOrder::query())->count(),
            WaterOrderStatus::finished => $this->getStatusBuilder(WaterOrderStatus::finished, AppWaterOrder::query())->count(),
            WaterOrderStatus::received => $this->getStatusBuilder(WaterOrderStatus::received, AppWaterOrder::query())->count(),
        ]);
    }

    /**
     * @param $order_no
     * @return mixed
     * 配送员接单
     */
    public function receipt($order_no)
    {
        $item = AppWaterOrder::query()->where("no", $order_no)->first();
        if (!$item) {
            abort(422, "预约不存在");
        }

        if ($item->status == WaterOrderStatus::created) {
            $item->status          = WaterOrderStatus::received;
            $item->receipt_user_id = auth()->id();
            $item->receipt_at      = now();
            $item->save();
        } else if ($item->status == WaterOrderStatus::finished) {
            abort(422, "订单已完成");
        } else if ($item->status == WaterOrderStatus::received) {
            abort(422, "配送员已结单");
        } else if ($item->status == WaterOrderStatus::canceled) {
            abort(422, "订单已取消");
        }
        return Response::success();
    }

    /**
     * @param $order_no
     * @return mixed
     * 完成配送
     */
    public function finish($order_no)
    {
        $item = AppWaterOrder::query()->where("no", $order_no)->first();
        if (!$item) {
            abort(422, "预约不存在");
        }

        if ($item->status == WaterOrderStatus::received) {
            $item->status      = WaterOrderStatus::finished;
            $item->finished_at = now();
            $item->save();
        } else if ($item->status == WaterOrderStatus::finished) {
            abort(422, "订单已完成");
        } else if ($item->status == WaterOrderStatus::received) {
            abort(422, "配送员已结单");
        } else if ($item->status == WaterOrderStatus::canceled) {
            abort(422, "订单已取消");
        } else if ($item->status == WaterOrderStatus::created) {
            abort(422, "订单未接单");
        }
        return Response::success();
    }
}
