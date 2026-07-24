<?php

namespace App\Services;

use App\Models\RegistrationInvite;
use Illuminate\Pagination\LengthAwarePaginator;

class InviteService
{
    public function getPaginatedList(): LengthAwarePaginator
    {
        return RegistrationInvite::with('creator')
            ->latest()
            ->paginate(10);
    }

    public function generate(string $role, ?string $email = null): RegistrationInvite
    {
        return RegistrationInvite::generate($role, email: $email);
    }
}