<?php

namespace App\Http\Resources\API\App\Characters;

use App\Http\Resources\API\App\Characters\Images\ImageResource;
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
        if(isset($data['character_icon']) AND $data['character_icon'] != null) $data['character_icon'] = new ImageResource($data['character_icon']);
        if(isset($data['character_side_icon']) AND $data['character_side_icon'] != null) $data['character_side_icon'] = new ImageResource($data['character_side_icon']);
        if(isset($data['character_gacha_card']) AND $data['character_gacha_card'] != null) $data['character_gacha_card'] = new ImageResource($data['character_gacha_card']);
        if(isset($data['character_gacha_splash']) AND $data['character_gacha_splash'] != null) $data['character_gacha_splash'] = new ImageResource($data['character_gacha_splash']);
        // if(isset($data['vision']) AND $data['vision'] != null) $data['vision'] = new VisionResource($data['vision']);
        // if(isset($data['weapon_type']) AND $data['weapon_type'] != null) $data['weapon_type'] = new WeaponTypeResource($data['weapon_type']);
        // if(isset($data['ascension_materials']) AND $data['ascension_materials'] != null) $data['ascension_materials'] = AscensionMaterialResource::collection($data['ascension_materials']);
        // if(isset($data['skill_ascension_materials']) AND $data['skill_ascension_materials'] != null) $data['skill_ascension_materials'] = AscensionMaterialResource::collection($data['skill_ascension_materials']);
        // if(isset($data['skill_ascension_materials']) AND $data['skill_ascension_materials'] != null) $data['skill_ascension_materials'] = AscensionMaterialResource::collection($data['skill_ascension_materials']);
        // if(isset($data['character_stats']) AND $data['character_stats'] != null) $data['character_stats'] = StatResource::collection($data['character_stats']);
        // if(isset($data['character_skill_ascensions']) AND $data['character_skill_ascensions'] != null) $data['character_skill_ascensions'] = SkillResource::collection($data['character_skill_ascensions']);
        return $data;
    }
}
