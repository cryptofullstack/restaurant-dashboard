<?php

namespace App\Http\Resources;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'catalog' => [
                "id" => $this->id,
                "title" => $this->title,
                "body" => $this->description,
                "image" => asset('uploads/category/'.$this->image),
                "position" => $this->position,
            ],
            "data" => ProductResource::collection($this->products)->sortBy('position')
        ];
    }
}
