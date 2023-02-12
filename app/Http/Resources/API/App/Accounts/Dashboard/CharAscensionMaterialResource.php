<?php

namespace App\Http\Resources\API\App\Accounts\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CharAscensionMaterialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = $this->resource;
        if(isset($data->character_icon) AND $data->character_icon != null) $data->character_icon = Storage::url($data->character_icon);
        if(isset($data->ascension_material_icon) AND $data->ascension_material_icon != null) $data->ascension_material_icon = Storage::url($data->ascension_material_icon);
        if(isset($data->quantity) AND $data->quantity != null) $data->quantity = (int) $data->quantity;
        return $data;
    }
}
