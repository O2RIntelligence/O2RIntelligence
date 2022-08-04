<?php

namespace App\Model\GoogleAds;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    use UsesUuid;
    protected $fillable=[
        'id',
        'date',
        'usdToArs',
    ];
}
