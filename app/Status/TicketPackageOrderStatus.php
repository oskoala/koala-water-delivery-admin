<?php


namespace App\Status;


class TicketPackageOrderStatus
{
    const created = "created";
    const paid = "paid";
    const finished = "finished";

    public static function getMap()
    {
        return [
            self::created  => "待支付",
            self::paid     => "已支付",
            self::finished => "已完成",
        ];
    }
}
