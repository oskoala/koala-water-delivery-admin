<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppTicketPackageOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "ticket_package_id",
        "no",
        "transaction_id",
        "total_price",
        "status",
        "snapshot",
        "paid_at",
        "closed_at",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
