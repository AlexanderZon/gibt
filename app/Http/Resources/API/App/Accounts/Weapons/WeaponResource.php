<?php

namespace App\Http\Resources\API\App\Accounts\Weapons;

use App\Http\Resources\API\App\Accounts\Characters\CharacterResource;
use App\Http\Resources\API\App\Weapons\WeaponResource as WeaponsWeaponResource;
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
        if(isset($data['weapon']) AND $data['weapon'] != null) $data['weapon'] = new WeaponsWeaponResource($data['weapon']);
        if(isset($data['account_character']) AND $data['account_character'] != null) $data['account_character'] = new CharacterResource($data['account_character']);
        return $data;
    }
}
