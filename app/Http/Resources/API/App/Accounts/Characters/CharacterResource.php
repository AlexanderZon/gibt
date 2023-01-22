<?php

namespace App\Http\Resources\API\App\Accounts\Characters;

use App\Http\Resources\API\App\Characters\CharacterResource as CharactersCharacterResource;
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
        if(isset($data['character']) AND $data['character'] != null) $data['character'] = new CharactersCharacterResource($data['character']);
        return $data;
    }
}