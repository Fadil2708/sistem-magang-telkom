<?php

namespace App\Livewire\Supervisor;

use App\Models\FinalReport;
use Livewire\Component;
use Livewire\WithPagination;

class ReportReview extends Component
{
    use WithPagination;

    private function baseQuery()
    {
        return FinalReport::with(['intern.internProfile', 'internship.vacancy'])
            ->whereHas('internship', fn($q) => $q->where('supervisor_id', auth()->id()));
    }

    public function render()
    {
        $reports = $this->baseQuery()
            ->latest('submitted_at')
            ->paginate(10);

        return view('livewire.supervisor.report-review', compact('reports'));
    }
}