<?php

namespace App\Model\GoogleAds;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubAccount extends Model
{
    use HasFactory,  SoftDeletes, UsesUuid;
    protected $fillable=[
        'id',
        'master_account_id',
        'name',
        'account_id',
        'is_active',
        'is_online',
    ];
}
