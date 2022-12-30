<?php

namespace App\Http\Resources\API\Admin\Weapons\Stats;

use App\Http\Resources\API\Admin\AscensionMaterials\AscensionMaterialResource;
use App\Http\Resources\API\Admin\StatTypes\StatTypeResource;
use App\Http\Resources\API\Admin\Weapons\WeaponResource;
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
        if(isset($data['weapon']) AND $data['weapon'] != null) $data['weapon'] = new WeaponResource($data['weapon']);
        if(isset($data['variable_stat']) AND $data['variable_stat'] != null) $data['variable_stat'] = new StatTypeResource($data['variable_stat']);
        if(isset($data['weap_primary_material']) AND $data['weap_primary_material'] != null) $data['weap_primary_material'] = new AscensionMaterialResource($data['weap_primary_material']);
        if(isset($data['weap_secondary_material']) AND $data['weap_secondary_material'] != null) $data['weap_secondary_material'] = new AscensionMaterialResource($data['weap_secondary_material']);
        if(isset($data['weap_common_item']) AND $data['weap_common_item'] != null) $data['weap_common_item'] = new AscensionMaterialResource($data['weap_common_item']);
        return $data;
    }
}
