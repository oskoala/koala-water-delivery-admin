<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppTicketType extends Model
{
    use HasFactory, HasDateTimeFormatter;

    protected $fillable = [
        "name",
        "image",
        "price",
        "min_buy_num",
    ];
}
