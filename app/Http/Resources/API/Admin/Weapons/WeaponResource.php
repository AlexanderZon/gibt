<?php

namespace App\Http\Resources\API\Admin\Weapons;

use App\Http\Resources\API\Admin\AscensionMaterials\AscensionMaterialResource;
use App\Http\Resources\API\Admin\Characters\Images\ImageResource;
use App\Http\Resources\API\Admin\Weapons\Stats\StatResource;
use App\Http\Resources\API\Admin\WeaponTypes\WeaponTypeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WeaponResource extends JsonResource
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
        if(isset($data['weapon_icon']) AND $data['weapon_icon'] != null) $data['weapon_icon'] = new ImageResource($data['weapon_icon']);
        if(isset($data['weapon_awakened_icon']) AND $data['weapon_awakened_icon'] != null) $data['weapon_awakened_icon'] = new ImageResource($data['weapon_awakened_icon']);
        if(isset($data['weapon_gacha_card']) AND $data['weapon_gacha_card'] != null) $data['weapon_gacha_card'] = new ImageResource($data['weapon_gacha_card']);
        if(isset($data['weapon_type']) AND $data['weapon_type'] != null) $data['weapon_type'] = new WeaponTypeResource($data['weapon_type']);
        if(isset($data['ascension_materials']) AND $data['ascension_materials'] != null) $data['ascension_materials'] = AscensionMaterialResource::collection($data['ascension_materials']);
        if(isset($data['weapon_stats']) AND $data['weapon_stats'] != null) $data['weapon_stats'] = StatResource::collection($data['weapon_stats']);
        return $data;
    }
}
