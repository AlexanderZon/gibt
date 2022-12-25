<?php

namespace App\Http\Resources\API\Admin\Characters;

use App\Http\Resources\API\Admin\AscensionMaterials\AscensionMaterialResource;
use App\Http\Resources\API\Admin\Elements\ElementResource;
use App\Http\Resources\API\Admin\Visions\VisionResource;
use App\Http\Resources\API\Admin\WeaponTypes\WeaponTypeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CharacterResource extends JsonResource
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
        // if(isset($data['icon']) AND $data['icon'] != null) $data['icon'] = Storage::url($data['icon']);
        if(isset($data['element']) AND $data['element'] != null) $data['element'] = new ElementResource($data['element']);
        if(isset($data['vision']) AND $data['vision'] != null) $data['vision'] = new VisionResource($data['vision']);
        if(isset($data['weapon_type']) AND $data['weapon_type'] != null) $data['weapon_type'] = new WeaponTypeResource($data['weapon_type']);
        if(isset($data['ascension_materials']) AND $data['ascension_materials'] != null) $data['ascension_materials'] = AscensionMaterialResource::collection($data['ascension_materials']);
        return $data;
    }
}
