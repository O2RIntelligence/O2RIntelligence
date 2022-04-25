<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminConfig extends Model
{
    protected $table = 'admin_config';
    protected $fillable = [
        'ms_channel_id'
    ];
}
