<?php

namespace App\Livewire\Intern;

use App\Models\Internship;
use App\Models\Logbook;
use Livewire\Component;

class LogbookForm extends Component
{
    public ?string $logbookId = null;
    public string $activity_date = '';
    public string $activities = '';
    public string $output = '';
    public bool $hasActiveInternship = false;
    public string $validationStatus = 'draft';

    protected function rules(): array
    {
        return [
            'activity_date' => 'required|date|before_or_equal:today',
            'activities'    => 'required|string|min:20',
            'output'        => 'required|string|min:10',
        ];
    }

    public function mount(?string $id = null): void
    {
        $internship = Internship::where('intern_id', auth()->id())
            ->where('status', 'active')
            ->latest()
            ->first();

        $this->hasActiveInternship = $internship !== null;

        if ($id) {
            $logbook = Logbook::where('intern_id', auth()->id())
                ->whereIn('validation_status', ['draft', 'revision_requested'])
                ->findOrFail($id);

            $this->logbookId = $logbook->id;
            $this->activity_date = $logbook->activity_date?->format('Y-m-d') ?? '';
            $this->activities = $logbook->activities;
            $this->output = $logbook->output;
            $this->validationStatus = $logbook->validation_status;
        }
    }

    public function save(): void
    {
        $this->validate();

        $internship = Internship::where('intern_id', auth()->id())
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$internship) {
            $this->addError('activity_date', 'Tidak ada magang aktif. Tidak dapat membuat logbook.');
            return;
        }

        $data = [
            'internship_id' => $internship->id,
            'intern_id' => auth()->id(),
            'activity_date' => $this->activity_date,
            'activities' => $this->activities,
            'output' => $this->output,
        ];

        $exists = Logbook::where('internship_id', $internship->id)
            ->where('activity_date', $this->activity_date)
            ->when($this->logbookId, fn($q) => $q->where('id', '!=', $this->logbookId))
            ->exists();

        if ($exists) {
            $this->addError('activity_date', 'Anda sudah mengisi logbook untuk tanggal ini.');
            return;
        }

        if ($this->logbookId) {
            $logbook = Logbook::where('intern_id', auth()->id())
                ->whereIn('validation_status', ['draft', 'revision_requested'])
                ->findOrFail($this->logbookId);

            $logbook->update($data);
            session()->flash('success', 'Logbook berhasil diperbarui.');
        } else {
            Logbook::create([
                ...$data,
                'validation_status' => 'draft',
            ]);
            session()->flash('success', 'Logbook berhasil dibuat.');
        }

        $this->redirect(route('intern.logbooks'), navigate: true);
    }

    public function render()
    {
        return view('livewire.intern.logbook-form');
    }
}
