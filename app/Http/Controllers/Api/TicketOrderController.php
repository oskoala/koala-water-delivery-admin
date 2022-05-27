<?php


namespace App\Http\Controllers\Api;


use App\Http\Services\TicketOrderService;
use App\Models\AppTicketOrder;
use App\Status\TicketOrderStatus;
use Illuminate\Http\Request;
use Jiannei\Response\Laravel\Support\Facades\Response;

class TicketOrderController
{
    /**
     * @param Request $request
     * @return mixed
     * 创建订单
     */
    public function create(Request $request)
    {
        $user_id        = auth()->id();
        $ticket_type_id = $request->input("ticket_type_id");
        $num            = $request->input("num");
        if (!$ticket_type_id) {
            abort(422, "请选择水票");
        }
        $service = new TicketOrderService();
        $order   = $service->create($user_id, $ticket_type_id, $num);

        return Response::success($order);
    }

    /**
     * @param $order_no
     * @return mixed
     * 获取订单信息
     */

    public function show($order_no)
    {
        $order = AppTicketOrder::query()->with([
            "ticket_type:id,name,image"
        ])->where("no", $order_no)->first();
        return Response::success($order);
    }

    /**
     * @param $order_no
     * @return mixed
     * 获取支付信息
     */

    public function pay($order_no)
    {
        $service = new TicketOrderService();
        $info    = $service->pay($order_no);
        return Response::success($info);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * 接受微信发来的通知
     */
    public function notify()
    {
        $service = new TicketOrderService();
        return $service->notify();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     * 我的购票记录
     */
    public function list(Request $request)
    {
        $params  = $request->input();
        $builder = AppTicketOrder::query()->with([
            "ticket_type:id,name,image"
        ]);
        $builder->where("status", TicketOrderStatus::finished);
        $builder->orderByDesc("created_at");
        $items = $builder->paginate();
        return Response::success($items);
    }
}
