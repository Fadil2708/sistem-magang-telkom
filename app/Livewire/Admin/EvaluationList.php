<?php

namespace App\Livewire\Admin;

use App\Services\EvaluationService;
use Livewire\Component;
use Livewire\WithPagination;

class EvaluationList extends Component
{
    use WithPagination;

    public $filterGrade = '';

    private EvaluationService $evaluationService;

    public function boot(EvaluationService $evaluationService): void
    {
        $this->evaluationService = $evaluationService;
    }

    public function updatingFilterGrade(): void { $this->resetPage(); }

    public function render()
    {
        $evaluations = $this->evaluationService->getAdminPaginatedList($this->filterGrade);
        return view('livewire.admin.evaluation-list', compact('evaluations'));
    }
}