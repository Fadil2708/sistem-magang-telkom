<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getPaginatedList(string $search = '', string $filterRole = ''): LengthAwarePaginator
    {
        return User::with(['internProfile', 'supervisorProfile'])
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhereHas('internProfile', fn($p) => $p->where('full_name', 'like', "%{$search}%"))
                  ->orWhereHas('supervisorProfile', fn($p) => $p->where('full_name', 'like', "%{$search}%"));
            }))
            ->when($filterRole, fn($q) => $q->where('role', $filterRole))
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function update(User $user, array $data): void
    {
        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);
    }

    public function toggleActive(User $user): bool
    {
        $user->update(['is_active' => !$user->is_active]);
        return $user->is_active;
    }
}