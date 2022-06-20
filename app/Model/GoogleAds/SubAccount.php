<?php

namespace App\Model\GoogleAds;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubAccount extends Model
{
    use SoftDeletes, UsesUuid;
    protected $fillable=[
        'id',
        'master_account_id',
        'name',
        'account_id',
        'timezone',
        'currency_code',
        'is_active',
        'is_online',
    ];

    public function masterAccount(){
        $this->belongsTo('sub_accounts','master_account_id');
    }
}
