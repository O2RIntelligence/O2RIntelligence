<?php

namespace App\Model;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class PixalateImpression extends Model
{
    use UsesUuid;
    protected $fillable=[
        'id',
        'date',
        'seat_id',
        'impressions',
    ];
}
