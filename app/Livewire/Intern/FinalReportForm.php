<?php

namespace App\Livewire\Intern;

use App\Models\FinalReport;
use Livewire\Component;
use Livewire\WithFileUploads;

class FinalReportForm extends Component
{
    use WithFileUploads;

    public $title = '';
    public $file;
    public $existingReport = null;

    protected $rules = [
        'title' => 'required|string|max:255',
        'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
    ];

    public function mount(): void
    {
        $this->existingReport = FinalReport::where('intern_id', auth()->id())
            ->latest()
            ->first();

        if ($this->existingReport) {
            $this->title = $this->existingReport->title;
        }
    }

    public function upload(): void
    {
        $this->validate();

        $internship = \App\Models\Internship::where('intern_id', auth()->id())
            ->where('status', 'active')
            ->firstOrFail();

        $fileUrl = $this->file->store('reports', 'public');

        if ($this->existingReport) {
            $this->existingReport->update([
                'title' => $this->title,
                'file_url' => $fileUrl,
                'submitted_at' => now(),
                'supervisor_approval' => 'pending',
            ]);
        } else {
            FinalReport::create([
                'internship_id' => $internship->id,
                'intern_id' => auth()->id(),
                'title' => $this->title,
                'file_url' => $fileUrl,
                'submitted_at' => now(),
            ]);
        }

        $this->dispatch('toast', message: 'Laporan akhir berhasil diunggah.', type: 'success');
    }

    public function render()
    {
        return view('livewire.intern.final-report-form');
    }
}