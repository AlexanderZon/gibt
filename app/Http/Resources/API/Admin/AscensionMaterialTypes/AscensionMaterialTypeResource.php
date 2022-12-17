<?php

namespace App\Http\Resources\API\Admin\AscensionMaterialTypes;

use Illuminate\Http\Resources\Json\JsonResource;

class AscensionMaterialTypeResource extends JsonResource
{

    // protected $wrap = 'list'; 

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        return $data;
    }
}
