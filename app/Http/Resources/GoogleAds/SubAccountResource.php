<?php

namespace App\Http\Resources\GoogleAds;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"=>$this->id,
            "master_account_id"=>$this->master_account_id,
            "name"=>$this->name,
            "account_id"=>$this->account_id,
            "timezone"=>$this->timezone,
            "currency_code"=>$this->currency_code,
            "is_active"=>$this->is_active,
            "is_online"=>$this->is_online,
        ];
    }
}
