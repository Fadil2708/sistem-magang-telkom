<?php

namespace App\Livewire\Admin;

use App\Services\LogbookService;
use Livewire\Component;
use Livewire\WithPagination;

class LogbookList extends Component
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
        $logbooks = $this->logbookService->getAdminPaginatedList($this->search, $this->filterStatus);
        return view('livewire.admin.logbook-list', compact('logbooks'));
    }
}