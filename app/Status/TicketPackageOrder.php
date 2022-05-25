<?php


namespace App\Status;


class TicketPackageOrder
{
    const create = "create";
    const paid = "paid";
    const finish = "finish";

    public static function getMap()
    {
        return [
            self::create => "待支付",
            self::paid   => "已支付",
            self::finish => "已完成",
        ];
    }
}
