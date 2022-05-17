<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppTicketPackage extends Model
{
    use HasFactory, HasDateTimeFormatter;

    protected $fillable = [
        "title",
        "image",
        "detail",
        "ticket_type_id",
        "num",
        "price",
        "scribing_price",
        "show",
        "order",
        "recommend",
    ];

    public function type()
    {
        return $this->belongsTo(AppTicketType::class, "ticket_type_id", "id");
    }
}
