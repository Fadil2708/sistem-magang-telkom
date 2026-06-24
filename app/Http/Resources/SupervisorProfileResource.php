<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupervisorProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'employee_id' => $this->employee_id,
            'division' => $this->division,
            'position' => $this->position,
            'phone' => $this->phone,
        ];
    }
}
