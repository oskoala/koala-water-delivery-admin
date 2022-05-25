<?php


namespace App\Http\Controllers\Api;


use App\Http\Services\TicketPackageOrderService;
use App\Models\AppTicketPackageOrder;
use Illuminate\Http\Request;

class TicketPackageOrderController
{
    /**
     * @param Request $request
     * @return mixed
     * 创建订单
     */
    public function create(Request $request)
    {
        $user_id           = auth()->id();
        $ticket_package_id = $request->input("ticket_package_id");
        if (!$ticket_package_id) {
            return \Response::errorBadRequest();
        }
        $service = new TicketPackageOrderService();
        $order   = $service->create($user_id, $ticket_package_id);

        return \Response::success($order);
    }

    /**
     * @param $order_no
     * @return mixed
     * 获取订单信息
     */

    public function show($order_no)
    {
        $order = AppTicketPackageOrder::query()->where("order_no", $order_no)->first();
        return \Response::success($order);
    }

    /**
     * @param $order_no
     * @return mixed
     * 获取支付信息
     */

    public function pay($order_no)
    {
        $service = new TicketPackageOrderService();
        $info    = $service->pay($order_no);
        return \Response::success($info);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * 接受微信发来的通知
     */
    public function notify()
    {
        $service = new TicketPackageOrderService();
        return $service->notify();
    }
}
