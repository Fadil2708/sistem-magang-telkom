<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InternProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'address' => $this->address,
            'date_of_birth' => $this->date_of_birth?->format('Y-m-d'),
            'institution_name' => $this->institution_name,
            'institution_type' => $this->institution_type,
            'major' => $this->major,
            'student_id' => $this->student_id,
            'photo_url' => $this->photo_url,
            'cv_url' => $this->cv_url,
            'cover_letter_url' => $this->cover_letter_url,
        ];
    }
}
