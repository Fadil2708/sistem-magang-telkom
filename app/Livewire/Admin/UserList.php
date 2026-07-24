<?php

namespace App\Livewire\Admin;

use App\Services\UserService;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterRole = '';
    public ?string $confirmingDeactivateId = null;

    private UserService $userService;

    public function boot(UserService $userService): void
    {
        $this->userService = $userService;
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterRole(): void { $this->resetPage(); }

    public function confirmDeactivate(string $id): void
    {
        $this->confirmingDeactivateId = $id;
    }

    public function toggleActive(): void
    {
        $user = \App\Models\User::findOrFail($this->confirmingDeactivateId);
        $active = $this->userService->toggleActive($user);
        $this->dispatch('toast', message: $active ? 'Pengguna diaktifkan.' : 'Pengguna dinonaktifkan.', type: 'success');
        $this->confirmingDeactivateId = null;
    }

    public function render()
    {
        $users = $this->userService->getPaginatedList($this->search, $this->filterRole);
        return view('livewire.admin.user-list', compact('users'));
    }
}