<?php

namespace App\Livewire\Admin;

use App\Models\Evaluation;
use Livewire\Component;
use Livewire\WithPagination;

class EvaluationList extends Component
{
    use WithPagination;

    public $filterGrade = '';

    public function updatingFilterGrade(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $evaluations = Evaluation::with([
            'internship.intern.internProfile',
            'internship.vacancy',
            'supervisor.supervisorProfile',
        ])
            ->when($this->filterGrade, fn($q) => $q->where('grade', $this->filterGrade))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.evaluation-list', compact('evaluations'));
    }
}
