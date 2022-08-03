<?php

namespace App\Model\GoogleAds;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterAccount extends Model
{
    use SoftDeletes, UsesUuid;
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

    public function subAccount() {
        $this->belongsToMany('id','sub_accounts');
    }
}
