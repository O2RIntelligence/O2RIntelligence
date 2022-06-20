<?php

namespace App\Model\GoogleAds;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HourlyData extends Model
{
    use SoftDeletes, UsesUuid;
    protected $fillable=[
        'id',
        'date',
        'hour',
        'sub_account_id',
        'cost',
        'cost_usd',
        'is_active',
        'is_online',
    ];
}
