<?php

namespace App\Livewire\Intern;

use App\Models\FinalReport;
use App\Models\Internship;
use App\Services\FileUploadService;
use Livewire\Component;
use Livewire\WithFileUploads;

class FinalReportForm extends Component
{
    use WithFileUploads;

    public string $title = '';
    public $file;
    public ?FinalReport $report = null;
    public ?Internship $internship = null;
    public bool $hasActiveInternship = false;
    public bool $canUpload = false;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:500',
            'file'  => 'required|file|mimetypes:application/pdf|max:20480',
        ];
    }

    public function mount(): void
    {
        $this->internship = Internship::where('intern_id', auth()->id())
            ->where('status', 'active')
            ->latest()
            ->first();

        $this->hasActiveInternship = $this->internship !== null;

        if ($this->internship) {
            $this->report = FinalReport::where('internship_id', $this->internship->id)->first();

            if ($this->report) {
                $this->title = $this->report->title;
                $this->canUpload = $this->report->supervisor_approval === 'rejected';
            } else {
                $this->canUpload = true;
            }
        }
    }

    public function save(FileUploadService $uploadService): void
    {
        $this->validate();

        $internship = Internship::where('intern_id', auth()->id())
            ->where('status', 'active')
            ->latest()
            ->firstOrFail();

        $fileUrl = $uploadService->uploadFinalReport($this->file, $internship->id);

        if (!$fileUrl) {
            session()->flash('error', 'Gagal mengupload file. Silakan coba lagi.');
            return;
        }

        $data = [
            'internship_id' => $internship->id,
            'intern_id' => auth()->id(),
            'title' => $this->title,
            'file_url' => $fileUrl,
            'file_size_kb' => (int) ($this->file->getSize() / 1024),
            'submitted_at' => now(),
            'supervisor_approval' => 'pending',
            'approved_at' => null,
        ];

        FinalReport::updateOrCreate(
            ['internship_id' => $internship->id],
            $data
        );

        session()->flash('success', 'Laporan akhir berhasil diupload.');
        $this->redirect(route('intern.reports'), navigate: true);
    }

    public function render()
    {
        return view('livewire.intern.final-report-form');
    }
}
