<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    public function view(User $user, Application $application): bool
    {
        if (!$application->relationLoaded('internship')) {
            $application->load('internship');
        }

        return match ($user->role) {
            'admin' => true,
            'supervisor' => $application->internship?->supervisor_id === $user->id,
            'intern' => $application->intern_id === $user->id,
            default => false,
        };
    }
}
