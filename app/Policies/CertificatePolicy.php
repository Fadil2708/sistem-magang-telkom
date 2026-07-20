<?php

namespace App\Policies;

use App\Models\Certificate;
use App\Models\User;

class CertificatePolicy
{
    public function view(User $user, Certificate $certificate): bool
    {
        if (!$certificate->relationLoaded('internship')) {
            $certificate->load('internship');
        }

        return match ($user->role) {
            'admin' => true,
            'supervisor' => $certificate->internship?->supervisor_id === $user->id,
            'intern' => $certificate->intern_id === $user->id,
            default => false,
        };
    }
}
