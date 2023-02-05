<?php

namespace App\Http\Resources\API\App\Accounts\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TalentBookResource extends JsonResource
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
        if(isset($data->talent_book_icon) AND $data->talent_book_icon != null) $data->talent_book_icon = Storage::url($data->talent_book_icon);
        return $data;
    }
}
