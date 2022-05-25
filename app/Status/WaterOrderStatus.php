<?php


namespace App\Status;


class WaterOrderStatus
{
    const created = "created";
    const received = "received";
    const finished = "finished";
    const canceled = "canceled";

    public static function getMap()
    {
        return [
            self::created  => "接单中",
            self::received => "配送中",
            self::finished => "已完成",
            self::canceled => "已取消",
        ];
    }
}
