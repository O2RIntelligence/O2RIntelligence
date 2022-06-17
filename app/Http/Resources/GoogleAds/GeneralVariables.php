<?php

namespace App\Http\Resources\GoogleAds;

use Illuminate\Http\Resources\Json\JsonResource;

class GeneralVariables extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
