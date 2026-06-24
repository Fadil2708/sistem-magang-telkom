<?php

namespace App\Livewire\Supervisor;

use App\Models\Evaluation;
use App\Models\Internship;
use Livewire\Component;

class EvaluationForm extends Component
{
    public ?string $internshipId = null;
    public ?Internship $internship = null;
    public ?Evaluation $evaluation = null;
    public bool $isLocked = false;

    public string $soft_skill_score = '';
    public string $hard_skill_score = '';
    public string $attendance_score = '';
    public string $attitude_score = '';
    public string $remarks = '';
    public bool $confirmingSave = false;

    protected function rules(): array
    {
        return [
            'soft_skill_score' => 'required|numeric|min:0|max:100',
            'hard_skill_score' => 'required|numeric|min:0|max:100',
            'attendance_score' => 'required|numeric|min:0|max:100',
            'attitude_score'   => 'required|numeric|min:0|max:100',
            'remarks'          => 'nullable|string|max:1000',
        ];
    }

    private function toast(string $message, string $type = 'success'): void
    {
        $this->dispatch('toast', message: $message, type: $type);
    }

    public function mount(?string $internshipId = null): void
    {
        if (!$internshipId) {
            return;
        }

        $this->internshipId = $internshipId;
        $this->internship = Internship::where('id', $internshipId)
            ->where('supervisor_id', auth()->id())
            ->with(['intern.internProfile', 'vacancy', 'evaluation', 'certificate'])
            ->first();

        if (!$this->internship) {
            $this->toast('Anda tidak berhak mengakses data ini.', 'error');
            return;
        }

        $this->evaluation = $this->internship->evaluation;
        $this->isLocked = $this->evaluation?->evaluated_at !== null || $this->internship->certificate !== null;

        if ($this->evaluation) {
            $this->soft_skill_score = (string) $this->evaluation->soft_skill_score;
            $this->hard_skill_score = (string) $this->evaluation->hard_skill_score;
            $this->attendance_score = (string) $this->evaluation->attendance_score;
            $this->attitude_score = (string) $this->evaluation->attitude_score;
            $this->remarks = $this->evaluation->remarks ?? '';
        }
    }

    public function confirmSave(): void
    {
        if ($this->isLocked) {
            $this->toast('Tidak bisa mengubah penilaian karena sertifikat sudah diterbitkan.', 'error');
            return;
        }

        if (!$this->internship) {
            $this->toast('Data magang tidak ditemukan.', 'error');
            return;
        }

        $fresh = Internship::where('id', $this->internship->id)
            ->where('supervisor_id', auth()->id())
            ->exists();

        if (!$fresh) {
            $this->toast('Anda tidak berhak menilai magang ini.', 'error');
            return;
        }

        $this->validate();
        $this->confirmingSave = true;
    }

    public function save(): void
    {
        $this->confirmingSave = false;

        if ($this->isLocked) {
            $this->toast('Penilaian sudah terkunci.', 'error');
            return;
        }

        $fresh = Internship::where('id', $this->internship->id)
            ->where('supervisor_id', auth()->id())
            ->exists();
        if (!$fresh) {
            $this->toast('Anda tidak berhak menilai magang ini.', 'error');
            return;
        }

        $s = (float) $this->soft_skill_score;
        $h = (float) $this->hard_skill_score;
        $att = (float) $this->attendance_score;
        $ati = (float) $this->attitude_score;
        $finalScore = ($s * 0.25) + ($h * 0.35) + ($att * 0.20) + ($ati * 0.20);
        $grade = match (true) {
            $finalScore >= 85 => 'A',
            $finalScore >= 70 => 'B',
            $finalScore >= 55 => 'C',
            default           => 'D',
        };

        $data = [
            'internship_id' => $this->internship->id,
            'supervisor_id' => auth()->id(),
            'soft_skill_score' => $s,
            'hard_skill_score' => $h,
            'attendance_score' => $att,
            'attitude_score' => $ati,
            'final_score' => $finalScore,
            'grade' => $grade,
            'remarks' => $this->remarks ?: null,
        ];

        $evaluation = Evaluation::updateOrCreate(
            ['internship_id' => $this->internship->id],
            $data
        );

        $this->evaluation = $evaluation->fresh();
        $this->toast('Penilaian berhasil disimpan. Nilai akhir: ' . number_format($finalScore, 0) . ' (Grade: ' . $grade . ')', 'success');
    }

    public function render()
    {
        $internships = collect();
        if (!$this->internshipId) {
            $internships = Internship::where('supervisor_id', auth()->id())
                ->where('status', 'completed')
                ->with(['intern.internProfile', 'vacancy', 'evaluation'])
                ->latest()
                ->get();
        }

        return view('livewire.supervisor.evaluation-form', compact('internships'));
    }
}
