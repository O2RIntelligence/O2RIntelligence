<?php

namespace App\Model\GoogleAds;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoogleGrantToken extends Model
{
    use SoftDeletes, UsesUuid;
    protected $fillable=[
        'id',
        'refresh_token',
        'access_token',
    ];
}
