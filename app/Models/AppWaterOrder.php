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
}
