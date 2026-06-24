<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'interview_date' => $this->interview_date?->format('Y-m-d H:i:s'),
            'rejection_reason' => $this->rejection_reason,
            'admin_notes' => $this->admin_notes,
            'applied_at' => $this->applied_at?->format('Y-m-d H:i:s'),
            'intern' => UserResource::make($this->whenLoaded('intern')),
            'vacancy' => VacancyResource::make($this->whenLoaded('vacancy')),
            'internship' => InternshipResource::make($this->whenLoaded('internship')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
