<?php


namespace App\Http\Services;


use App\Models\AppTicketOrder;
use App\Models\AppTicketType;
use App\Models\AppUserTicket;
use App\Status\TicketOrderStatus;
use EasyWeChat\Factory;

class TicketOrderService
{
    /**
     * @param $user_id
     * @param $ticket_type_id
     * @param $num
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * 创建订单
     */
    public function create($user_id, $ticket_type_id, $num)
    {
        $order_no    = get_unique_no(10);
        $ticket_type = AppTicketType::query()->find($ticket_type_id);
        if ($ticket_type->min_buy_num > $num) {
            abort(422, "该水票最少购买" . $ticket_type->min_buy_num . "张");
        }
        $order = AppTicketOrder::query()->create([
            "user_id"        => $user_id,
            "ticket_type_id" => $ticket_type_id,
            "no"             => $order_no,
            "total_price"    => $ticket_type->price * $num,
            "num"            => $num,
            "status"         => TicketOrderStatus::created,
            "snapshot"       => $ticket_type,
        ]);
        return $order;
    }

    /**
     * @param $order_no
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * 调用微信支付，返回支付参数
     */
    public function pay($order_no)
    {
        $config = [
            'app_id' => env("WECHAT_MINI_APP_APPID"),
            'mch_id' => env("WECHAT_MCH_ID"),
            'key'    => env("WECHAT_MCH_KEY"),
        ];
        $app    = Factory::payment($config);
        $jssdk  = $app->jssdk;

        $order = AppTicketOrder::query()->where("no", $order_no)->first();

        $user = auth()->user();

        $result = $app->order->unify([
            'body'         => "水票购买",         // 订单说明
            'out_trade_no' => $order_no,   // 平台内部订单号
            'total_fee'    => $order->total_price * 100,   // 价格, 单位为分
            'notify_url'   => env("APP_URL") . '/api/v1/ticket_order_pay_notify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'trade_type'   => 'JSAPI', // 请对应换成你的支付方式对应的值类型 小程序为JSAPI
            'openid'       => $user->openid,
        ]);

        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
            $prepayId = $result['prepay_id'];
            $config   = $jssdk->sdkConfig($prepayId);
            return $config;
        }

        if ($result['return_code'] == 'FAIL' && array_key_exists('return_msg', $result)) {
            abort("422", $result['return_msg']);
        }
        abort("422", $result['err_code_des']);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     * 接受微信发来的通知
     */
    public function notify()
    {
        $config   = [
            'app_id' => env("WECHAT_MINI_APP_APPID"),
            'mch_id' => env("WECHAT_MCH_ID"),
            'key'    => env("WECHAT_MCH_KEY"),
        ];
        $app      = Factory::payment($config);
        $response = $app->handlePaidNotify(function ($message, $fail) {
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = AppTicketOrder::query()->where("no", $message['out_trade_no'])->first();

            if (!$order || $order->paid_at) { // 如果订单不存在 或者 订单已经支付过了
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////

            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if ($message['result_code'] === 'SUCCESS') {
                    $transaction_id        = $message['transaction_id'];
                    $order->transaction_id = $transaction_id;
                    $order->paid_at        = time(); // 更新支付时间为当前时间
                    $order->status         = TicketOrderStatus::paid;
                    //处理付款成功订单
                    $this->handlePaidTicketOrder($order);
                    $order->status = TicketOrderStatus::finished;
                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }

            $order->save(); // 保存订单

            return true; // 返回处理完成
        });

        return $response;
    }

    /**
     * @param $order
     * 处理付款订单
     */
    public function handlePaidTicketOrder($order)
    {
        $ticket_type    = AppTicketType::query()->where("id", $order->ticket_type_id)->first();
        $num            = $order->num;
        $user_id        = $order->user_id;
        $ticket_type_id = $ticket_type->id;
        //用户已拥有这种类型水票，直接添加
        if (AppUserTicket::query()->where("user_id", $user_id)->where("ticket_type_id", $ticket_type_id)->exists()) {
            $ticket = AppUserTicket::query()->where("user_id", $user_id)->where("ticket_type_id", $ticket_type_id)->first();
            $ticket->update([
                "num" => $ticket->num + $num
            ]);
        } else {
            //没有这种水票，直接创建
            AppUserTicket::query()->create([
                "ticket_type_id" => $ticket_type_id,
                "user_id"        => $user_id,
                "num"            => $num,
            ]);
        }
    }
}
