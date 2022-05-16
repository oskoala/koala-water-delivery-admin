<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class AppUser extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use HasDateTimeFormatter;

    protected $fillable = [
        "openid",
        "nickname",
        "avatar",
        "mobile",
        "address_id",
    ];

    protected $hidden = [
        'openid'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
