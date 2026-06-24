<?php

namespace App\Livewire\Supervisor;

use App\Models\Internship;
use Livewire\Component;
use Livewire\WithPagination;

class MyInterns extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = 'active';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $internships = Internship::where('supervisor_id', auth()->id())
            ->with(['intern.internProfile', 'vacancy'])
            ->withCount([
                'logbooks as total_logbooks',
                'logbooks as approved_logbooks' => fn($q) => $q->where('validation_status', 'approved'),
            ])
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->where(function ($q) {
                if (strlen($this->search) > 0) {
                    $q->whereHas('intern.internProfile', fn($q) => $q->where('full_name', 'like', '%' . $this->search . '%'))
                      ->orWhereHas('intern', fn($q) => $q->where('email', 'like', '%' . $this->search . '%'));
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.supervisor.my-interns', compact('internships'));
    }
}
