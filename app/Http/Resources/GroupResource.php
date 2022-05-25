<?php

namespace App\Http\Resources;

use App\Http\Resources\ExtraResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->group_name,
            "type" => $this->type,
            "position" => $this->pivot->position,
            'extras' => ExtraResource::collection($this->extras)->sortBy('position')
        ];
    }
}
