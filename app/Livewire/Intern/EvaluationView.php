<?php

namespace App\Livewire\Intern;

use App\Models\Evaluation;
use Livewire\Component;

class EvaluationView extends Component
{
    public $evaluation = null;

    public function mount(): void
    {
        $this->evaluation = Evaluation::with(['supervisor.supervisorProfile', 'internship.vacancy'])
            ->whereHas('internship', fn($q) => $q->where('intern_id', auth()->id()))
            ->latest()
            ->first();
    }

    public function render()
    {
        return view('livewire.intern.evaluation-view');
    }
}