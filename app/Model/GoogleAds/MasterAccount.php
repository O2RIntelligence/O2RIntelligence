<?php

namespace App\Model\GoogleAds;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterAccount extends Model
{
    use HasFactory,  SoftDeletes, UsesUuid;
    protected $fillable=[
        'id',
        'name',
        'account_id',
        'developer_token',
        'discount',
        'revenue_conversion_rate',
        'is_active',
        'is_online',
    ];
}
