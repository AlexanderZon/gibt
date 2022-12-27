<?php

namespace App\Http\Resources\API\Admin\Characters\Stats;

use App\Http\Resources\API\Admin\AscensionMaterials\AscensionMaterialResource;
use App\Http\Resources\API\Admin\Characters\CharacterResource;
use App\Http\Resources\API\Admin\StatTypes\StatTypeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StatResource extends JsonResource
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
        if(isset($data['variable_stat']) AND $data['variable_stat'] != null) $data['variable_stat'] = new StatTypeResource($data['variable_stat']);
        if(isset($data['char_jewel']) AND $data['char_jewel'] != null) $data['char_jewel'] = new AscensionMaterialResource($data['char_jewel']);
        if(isset($data['char_elemental_stone']) AND $data['char_elemental_stone'] != null) $data['char_elemental_stone'] = new AscensionMaterialResource($data['char_elemental_stone']);
        if(isset($data['char_local_material']) AND $data['char_local_material'] != null) $data['char_local_material'] = new AscensionMaterialResource($data['char_local_material']);
        if(isset($data['char_common_item']) AND $data['char_common_item'] != null) $data['char_common_item'] = new AscensionMaterialResource($data['char_common_item']);
        return $data;
    }
}
