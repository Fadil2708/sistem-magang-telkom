<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'content' => $this->content,
            'is_published' => $this->is_published,
            'intern' => UserResource::make($this->whenLoaded('intern')),
            'internship_id' => $this->internship_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
