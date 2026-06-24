<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VacancyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'division' => $this->division,
            'description' => $this->description,
            'qualifications' => $this->qualifications,
            'quota' => $this->quota,
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'application_deadline' => $this->application_deadline?->format('Y-m-d'),
            'status' => $this->status,
            'created_by' => UserResource::make($this->whenLoaded('creator')),
            'applications_count' => $this->whenCounted('applications'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
