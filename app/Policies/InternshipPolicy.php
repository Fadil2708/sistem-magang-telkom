<?php

namespace App\Policies;

use App\Models\Internship;
use App\Models\User;

class InternshipPolicy
{
    public function view(User $user, Internship $internship): bool
    {
        return match ($user->role) {
            'admin' => true,
            'supervisor' => $internship->supervisor_id === $user->id,
            'intern' => $internship->intern_id === $user->id,
            default => false,
        };
    }
}
