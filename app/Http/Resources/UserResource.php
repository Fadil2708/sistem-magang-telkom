<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'role' => $this->role,
            'is_active' => $this->is_active,
            'intern_profile' => InternProfileResource::make($this->whenLoaded('internProfile')),
            'supervisor_profile' => SupervisorProfileResource::make($this->whenLoaded('supervisorProfile')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
