<?php

namespace App\Http\Resources\GoogleAds;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MasterAccountResource extends JsonResource
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
            "name"=>$this->name,
            "account_id"=>$this->account_id,
            "developer_token"=>$this->developer_token,
            "discount"=>$this->discount,
            "revenue_conversion_rate"=>$this->revenue_conversion_rate,
            "is_active"=>$this->is_active,
            "is_online"=>$this->is_online,
        ];
    }
}
