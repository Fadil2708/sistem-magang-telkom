<?php

namespace App\Livewire\Intern;

use App\Models\Internship;
use Livewire\Component;

class EvaluationView extends Component
{
    public ?Internship $internship = null;
    public ?string $scoreLabel = null;
    public ?string $scoreColor = null;

    public function mount(): void
    {
        $this->internship = Internship::where('intern_id', auth()->id())
            ->whereIn('status', ['completed', 'terminated'])
            ->with(['evaluation', 'vacancy', 'supervisor.supervisorProfile'])
            ->latest()
            ->first();
    }

    public function render()
    {
        return view('livewire.intern.evaluation-view');
    }
}
