<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EvaluationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'soft_skill_score' => $this->soft_skill_score,
            'hard_skill_score' => $this->hard_skill_score,
            'attendance_score' => $this->attendance_score,
            'attitude_score' => $this->attitude_score,
            'final_score' => $this->final_score,
            'grade' => $this->grade,
            'remarks' => $this->remarks,
            'evaluated_at' => $this->evaluated_at?->format('Y-m-d H:i:s'),
            'supervisor' => UserResource::make($this->whenLoaded('supervisor')),
            'internship_id' => $this->internship_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
