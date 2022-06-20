<?php

namespace App\Model\GoogleAds;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralVariable extends Model
{
    use SoftDeletes, UsesUuid;
    protected $fillable=[
        'id',
        'official_dollar',
        'blue_dollar',
        'plus_m_discount',
        'is_active',
    ];
}
