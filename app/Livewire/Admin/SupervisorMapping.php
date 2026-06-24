<?php

namespace App\Livewire\Admin;

use App\Models\Internship;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class SupervisorMapping extends Component
{
    use WithPagination;

    public $filterStatus = 'active';

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function assignSupervisor(string $internshipId, string $supervisorId): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $internship = Internship::findOrFail($internshipId);
        $supervisor = User::findOrFail($supervisorId);

        if ($supervisor->role !== 'supervisor' || !$supervisor->is_active) {
            $this->dispatch('toast', message: 'Pembimbing tidak valid.', type: 'error');
            return;
        }

        $internship->update(['supervisor_id' => $supervisorId]);
        $this->dispatch('toast', message: 'Pembimbing berhasil ditugaskan.', type: 'success');
    }

    public function render()
    {
        $internships = Internship::with(['intern.internProfile', 'supervisor.supervisorProfile', 'vacancy'])
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $supervisors = User::where('role', 'supervisor')
            ->where('is_active', true)
            ->with('supervisorProfile')
            ->get();

        return view('livewire.admin.supervisor-mapping', compact('internships', 'supervisors'));
    }
}
