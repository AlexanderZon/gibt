<?php

namespace App\Http\Resources\API\Admin\Characters\Skills;

use App\Http\Resources\API\Admin\AscensionMaterials\AscensionMaterialResource;
use App\Http\Resources\API\Admin\Characters\CharacterResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SkillResource extends JsonResource
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
        if(isset($data['character']) AND $data['character'] != null) $data['character'] = new CharacterResource($data['character']);
        if(isset($data['talent_book_item']) AND $data['talent_book_item'] != null) $data['talent_book_item'] = new AscensionMaterialResource($data['talent_book_item']);
        if(isset($data['talent_boss_item']) AND $data['talent_boss_item'] != null) $data['talent_boss_item'] = new AscensionMaterialResource($data['talent_boss_item']);
        if(isset($data['reward_item']) AND $data['reward_item'] != null) $data['reward_item'] = new AscensionMaterialResource($data['reward_item']);
        if(isset($data['char_common_item']) AND $data['char_common_item'] != null) $data['char_common_item'] = new AscensionMaterialResource($data['char_common_item']);
        return $data;
    }
}
