<?php

namespace App\Livewire\Admin;

use App\Models\Logbook;
use Livewire\Component;
use Livewire\WithPagination;

class LogbookList extends Component
{
    use WithPagination;

    public $filterStatus = '';
    public $search = '';

    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingSearch(): void { $this->resetPage(); }

    public function render()
    {
        $logbooks = Logbook::with(['intern.internProfile', 'internship.vacancy'])
            ->when($this->search, fn($q) => $q->whereHas('intern.internProfile', fn($p) =>
                $p->where('full_name', 'like', "%{$this->search}%")
            ))
            ->when($this->filterStatus, fn($q) => $q->where('validation_status', $this->filterStatus))
            ->orderBy('activity_date', 'desc')
            ->paginate(15);

        return view('livewire.admin.logbook-list', compact('logbooks'));
    }
}
