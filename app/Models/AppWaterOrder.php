<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppWaterOrder extends Model
{
    use HasFactory;
    use HasDateTimeFormatter;

    protected $fillable = [
        "user_id",
        "ticket_type_id",
        "receipt_user_id",
        "no",
        "num",
        "address",
        "status",
        "closed_at",
        "receipt_at",
    ];

    protected $casts = [
        "address" => "json",
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * 关联叫水类型
     */
    public function ticket_type()
    {
        return $this->hasOne(AppTicketType::class, "id", "ticket_type_id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * 关联配送员
     */
    public function receipt_user()
    {
        return $this->hasOne(AppUser::class, "id", "receipt_user_id");
    }
}
