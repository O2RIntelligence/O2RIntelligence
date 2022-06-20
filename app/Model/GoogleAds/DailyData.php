<?php

namespace App\Model\GoogleAds;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyData extends Model
{
    use SoftDeletes, UsesUuid;
    protected $fillable=[
        'id',
        'date',
        'sub_account_id',
        'cost',
        'cost_usd',
        'discount',
        'google_media_cost',
        'plus_m_share',
        'total_cost',
        'net_income',
        'net_income_percent',
        'account_budget',
        'budget_usage_percent',
        'monthly_run_rate',
        'is_active',
    ];
}
