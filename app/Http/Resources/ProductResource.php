<?php

namespace App\Http\Resources;

use App\Http\Resources\GroupResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            "title" => $this->pro_name,
            "description" => $this->pro_description,
            "price" => $this->pro_price,
            "image" => asset('uploads/products/'.$this->pro_image),
            "position" => $this->pivot->position,
            "groups" => GroupResource::collection($this->groups)->sortBy('position')
        ];
    }
}
