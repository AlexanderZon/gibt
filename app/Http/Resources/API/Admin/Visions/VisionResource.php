<?php

namespace App\Http\Resources\API\Admin\Visions;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class VisionResource extends JsonResource
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
        if($data['icon'] != null) $data['icon'] = Storage::url($data['icon']);
        return $data;
    }
}
