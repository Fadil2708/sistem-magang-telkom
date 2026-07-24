<?php

namespace App\Livewire\Supervisor;

use App\Services\LogbookService;
use Livewire\Component;
use Livewire\WithPagination;

class LogbookReview extends Component
{
    use WithPagination;

    public $filterStatus = '';
    public $search = '';

    private LogbookService $logbookService;

    public function boot(LogbookService $logbookService): void
    {
        $this->logbookService = $logbookService;
    }

    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingSearch(): void { $this->resetPage(); }

    public function render()
    {
        $logbooks = $this->logbookService->getSupervisorPaginatedList(
            auth()->id(),
            $this->filterStatus,
            $this->search
        );
        return view('livewire.supervisor.logbook-review', compact('logbooks'));
    }
}