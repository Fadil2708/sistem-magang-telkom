<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CertificateResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'certificate_number' => $this->certificate_number,
            'final_score' => $this->final_score,
            'grade' => $this->grade,
            'qr_code_url' => $this->qr_code_url,
            'certificate_file_url' => $this->certificate_file_url,
            'issued_at' => $this->issued_at?->format('Y-m-d H:i:s'),
            'issued_by' => UserResource::make($this->whenLoaded('issuedBy')),
            'intern' => UserResource::make($this->whenLoaded('intern')),
            'internship_id' => $this->internship_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
