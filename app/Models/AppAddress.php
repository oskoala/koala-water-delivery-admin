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
}
