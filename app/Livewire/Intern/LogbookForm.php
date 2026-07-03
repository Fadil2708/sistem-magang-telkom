<?php

namespace App\Livewire\Intern;

use App\Models\Internship;
use App\Models\Logbook;
use App\Services\LogbookService;
use Livewire\Component;

class LogbookForm extends Component
{
    public ?string $logbookId = null;
    public string $activity_date = '';
    public string $activities = '';
    public string $output = '';
    public bool $hasActiveInternship = false;
    public string $validationStatus = 'draft';

    private LogbookService $logbookService;

    public function boot(LogbookService $logbookService): void
    {
        $this->logbookService = $logbookService;
    }

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
            'activity_date' => $this->activity_date,
            'activities' => $this->activities,
            'output' => $this->output,
            'validation_status' => 'draft',
        ];

        try {
            if ($this->logbookId) {
                $logbook = Logbook::where('intern_id', auth()->id())
                    ->whereIn('validation_status', ['draft', 'revision_requested'])
                    ->findOrFail($this->logbookId);

                $this->logbookService->update($logbook, auth()->user(), $data);
                session()->flash('success', 'Logbook berhasil diperbarui.');
            } else {
                $this->logbookService->create($internship->id, auth()->user(), $data);
                session()->flash('success', 'Logbook berhasil dibuat.');
            }
        } catch (\Exception $e) {
            $this->addError('activity_date', $e->getMessage());
            return;
        }

        $this->redirect(route('intern.logbooks'), navigate: true);
    }

    public function render()
    {
        return view('livewire.intern.logbook-form');
    }
}
