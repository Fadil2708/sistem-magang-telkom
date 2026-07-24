<?php

namespace App\Livewire\Supervisor;

use App\Models\Evaluation;
use App\Services\EvaluationService;
use Livewire\Component;

class EvaluationForm extends Component
{
    public ?string $internshipId = null;
    public $internship;
    public $evaluation;
    public $completedInternships;

    public bool $showForm = false;

    public float $soft_skill_score = 0;
    public float $hard_skill_score = 0;
    public float $attendance_score = 0;
    public float $attitude_score = 0;
    public string $remarks = '';

    private EvaluationService $evaluationService;

    public function boot(EvaluationService $evaluationService): void
    {
        $this->evaluationService = $evaluationService;
    }

    protected $rules = [
        'soft_skill_score' => 'required|numeric|min:0|max:100',
        'hard_skill_score' => 'required|numeric|min:0|max:100',
        'attendance_score' => 'required|numeric|min:0|max:100',
        'attitude_score' => 'required|numeric|min:0|max:100',
        'remarks' => 'nullable|string',
    ];

    public function mount(?string $internshipId = null): void
    {
        $data = $this->evaluationService->getSupervisorEvaluations(auth()->id(), $internshipId);

        $this->internship = $data['internship'];
        $this->evaluation = $data['evaluation'];
        $this->completedInternships = $data['completedInternships'];
        $this->internshipId = $internshipId;

        if ($data['evaluation']) {
            $this->showForm = true;
            $this->soft_skill_score = $data['evaluation']->soft_skill_score;
            $this->hard_skill_score = $data['evaluation']->hard_skill_score;
            $this->attendance_score = $data['evaluation']->attendance_score;
            $this->attitude_score = $data['evaluation']->attitude_score;
            $this->remarks = $data['evaluation']->remarks ?? '';
        }
    }

    public function selectInternship(string $id): void
    {
        $this->mount($id);
    }

    public function save(): void
    {
        $this->validate();

        if ($this->evaluation) {
            $this->evaluation->update([
                'soft_skill_score' => $this->soft_skill_score,
                'hard_skill_score' => $this->hard_skill_score,
                'attendance_score' => $this->attendance_score,
                'attitude_score' => $this->attitude_score,
                'remarks' => $this->remarks,
            ]);
        } else {
            $this->evaluation = Evaluation::create([
                'internship_id' => $this->internshipId,
                'supervisor_id' => auth()->id(),
                'soft_skill_score' => $this->soft_skill_score,
                'hard_skill_score' => $this->hard_skill_score,
                'attendance_score' => $this->attendance_score,
                'attitude_score' => $this->attitude_score,
                'remarks' => $this->remarks,
            ]);
        }

        $this->evaluationService->calculateScore($this->evaluation);
        $this->dispatch('toast', message: 'Evaluasi berhasil disimpan.', type: 'success');
    }

    public function render()
    {
        return view('livewire.supervisor.evaluation-form');
    }
}