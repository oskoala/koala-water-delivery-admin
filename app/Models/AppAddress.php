<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "name",
        "phone",
        "province",
        "city",
        "district",
        "detail",
        "is_default",
        "lat",
        "lon",
    ];

    protected $appends = [
        'out_deliver_scope',// 标记是否超出配送范围
    ];

    public function getOutDeliverScopeAttribute()
    {
        // 计算是否超出配送范围
        $limit_deliver_scope = custom_config("deliver_scope");
        $shop_lat            = custom_config("shop_lat");
        $shop_lon            = custom_config("shop_lon");

        if ($this->lon && $this->lat) {
            if (cal_distance($shop_lat, $shop_lon, $this->lat, $this->lon) <= $limit_deliver_scope) {
                return false;
            }
        }
        return true;
    }
}
