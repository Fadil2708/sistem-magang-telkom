<?php

namespace App\Livewire\Admin;

use App\Models\Certificate;
use App\Models\Internship;
use App\Services\CertificateService;
use App\Services\NotificationService;
use Livewire\Component;
use Livewire\WithPagination;

class CertificateList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterGrade = '';
    public $confirmingIssueId = null;

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterGrade(): void { $this->resetPage(); }

    public function confirmIssue(string $internshipId): void
    {
        $this->confirmingIssueId = $internshipId;
    }

    public function issue(CertificateService $service, NotificationService $notificationService): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $internship = Internship::with('evaluation')
            ->where('status', 'completed')
            ->findOrFail($this->confirmingIssueId);

        if (!$internship->evaluation) {
            $this->dispatch('toast', message: 'Penilaian belum diisi oleh pembimbing.', type: 'error');
            $this->confirmingIssueId = null;
            return;
        }

        if ($internship->certificate) {
            $this->dispatch('toast', message: 'Sertifikat sudah diterbitkan.', type: 'warning');
            $this->confirmingIssueId = null;
            return;
        }

        $certificate = $service->issue($internship, auth()->id());

        dispatch(new \App\Jobs\SendCertificateNotificationJob($certificate));

        dispatch(new \App\Jobs\GenerateCertificatePdfJob($certificate));

        $this->confirmingIssueId = null;
        $this->dispatch('toast', message: 'Sertifikat berhasil diterbitkan.', type: 'success');
    }

    public function render()
    {
        $certificates = Certificate::with(['intern.internProfile', 'issuedBy'])
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('certificate_number', 'like', "%{$this->search}%")
                  ->orWhereHas('intern.internProfile', fn($p) => $p->where('full_name', 'like', "%{$this->search}%"));
            }))
            ->when($this->filterGrade, fn($q) => $q->where('grade', $this->filterGrade))
            ->orderBy('issued_at', 'desc')
            ->paginate(10);

        $pendingInternships = Internship::with(['intern.internProfile', 'vacancy', 'evaluation', 'certificate'])
            ->where('status', 'completed')
            ->whereDoesntHave('certificate')
            ->get();

        return view('livewire.admin.certificate-list', compact('certificates', 'pendingInternships'));
    }
}
