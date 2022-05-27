<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppTicketOrder extends Model
{
    use HasFactory;

    use HasDateTimeFormatter;

    protected $fillable = [
        "user_id",
        "ticket_type_id",
        "no",
        "transaction_id",
        "total_price",
        "num",
        "status",
        "snapshot",
        "paid_at",
        "closed_at",
    ];

    protected $casts = [
        "snapshot" => "json",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * 关联水票信息
     */
    public function ticket_type()
    {
        return $this->hasOne(AppTicketType::class, "id", "ticket_type_id");
    }
}
