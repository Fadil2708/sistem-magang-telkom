<?php

namespace App\Livewire\Intern;

use App\Models\Logbook;
use App\Services\LogbookService;
use Livewire\Component;
use Livewire\WithPagination;

class LogbookList extends Component
{
    use WithPagination;

    private LogbookService $logbookService;

    public function boot(LogbookService $logbookService): void
    {
        $this->logbookService = $logbookService;
    }

    public function render()
    {
        $logbooks = Logbook::with('internship')
            ->where('intern_id', auth()->id())
            ->latest('activity_date')
            ->paginate(15);

        return view('livewire.intern.logbook-list', compact('logbooks'));
    }
}