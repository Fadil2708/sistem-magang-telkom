<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterRole = '';
    public $confirmingDeactivateId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterRole(): void
    {
        $this->resetPage();
    }

    public function confirmDeactivate(string $id): void
    {
        $this->confirmingDeactivateId = $id;
    }

    public function deactivate(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $user = User::findOrFail($this->confirmingDeactivateId);

        if ($user->id === auth()->id()) {
            $this->dispatch('toast', message: 'Tidak bisa menonaktifkan akun sendiri.', type: 'error');
            $this->confirmingDeactivateId = null;
            return;
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->fresh()->is_active ? 'diaktifkan' : 'dinonaktifkan';
        $this->dispatch('toast', message: "Akun berhasil {$status}.", type: 'success');
        $this->confirmingDeactivateId = null;
    }

    public function render()
    {
        $users = User::with(['internProfile', 'supervisorProfile'])
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('email', 'like', "%{$this->search}%")
                  ->orWhereHas('internProfile', fn($p) => $p->where('full_name', 'like', "%{$this->search}%"))
                  ->orWhereHas('supervisorProfile', fn($p) => $p->where('full_name', 'like', "%{$this->search}%"));
            }))
            ->when($this->filterRole, fn($q) => $q->where('role', $this->filterRole))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.user-list', compact('users'));
    }
}
