<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InternshipResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'actual_start_date' => $this->actual_start_date?->format('Y-m-d'),
            'actual_end_date' => $this->actual_end_date?->format('Y-m-d'),
            'application' => ApplicationResource::make($this->whenLoaded('application')),
            'intern' => UserResource::make($this->whenLoaded('intern')),
            'supervisor' => $this->whenLoaded('supervisor', fn() => UserResource::make($this->supervisor)),
            'vacancy' => VacancyResource::make($this->whenLoaded('vacancy')),
            'logbooks_count' => $this->whenCounted('logbooks'),
            'approved_logbooks_count' => $this->whenCounted('approvedLogbooks'),
            'final_report' => FinalReportResource::make($this->whenLoaded('finalReport')),
            'evaluation' => EvaluationResource::make($this->whenLoaded('evaluation')),
            'certificate' => CertificateResource::make($this->whenLoaded('certificate')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
