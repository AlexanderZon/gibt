<?php

namespace App\Http\Resources\API\App\Characters\Images;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        if(isset($data['url']) AND $data['url'] != null) $data['url'] = Storage::url($data['url']);
        return $data;
    }
}
