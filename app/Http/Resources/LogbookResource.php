<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LogbookResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'activity_date' => $this->activity_date?->format('Y-m-d'),
            'activities' => $this->activities,
            'output' => $this->output,
            'validation_status' => $this->validation_status,
            'supervisor_notes' => $this->supervisor_notes,
            'reviewed_at' => $this->reviewed_at?->format('Y-m-d H:i:s'),
            'intern' => UserResource::make($this->whenLoaded('intern')),
            'internship_id' => $this->internship_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
