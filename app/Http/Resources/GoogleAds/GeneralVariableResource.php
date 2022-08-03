<?php

namespace App\Http\Resources\GoogleAds;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeneralVariableResource extends JsonResource
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
            'id'=>$this->id,
            'official_dollar'=>$this->official_dollar,
            'blue_dollar'=>$this->blue_dollar,
            'plus_m_discount'=>$this->plus_m_discount,
            'is_active'=>$this->is_active,
        ];
    }
}
