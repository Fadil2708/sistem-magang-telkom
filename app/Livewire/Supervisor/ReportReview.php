<?php

namespace App\Livewire\Supervisor;

use App\Jobs\SendReportNotificationJob;
use App\Models\FinalReport;
use App\Services\NotificationService;
use Livewire\Component;
use Livewire\WithPagination;

class ReportReview extends Component
{
    use WithPagination;

    public string $filterStatus = 'pending';

    private NotificationService $notificationService;

    public function boot(NotificationService $notificationService): void
    {
        $this->notificationService = $notificationService;
    }

    private function toast(string $message, string $type = 'success'): void
    {
        $this->dispatch('toast', message: $message, type: $type);
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function approve(string $id): void
    {
        try {
            $report = FinalReport::whereHas('internship', fn($q) =>
                $q->where('supervisor_id', auth()->id())
            )->with(['intern.internProfile'])->findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->toast('Laporan tidak ditemukan.', 'error');
            return;
        }

        if ($report->supervisor_approval !== 'pending') {
            $this->toast('Laporan ini sudah direview.', 'error');
            return;
        }

        $report->update([
            'supervisor_approval' => 'approved',
            'approved_at' => now(),
        ]);

        SendReportNotificationJob::dispatch(
            $this->notificationService->sendReportApproved($report)
        );

        $this->toast('Laporan akhir berhasil disetujui.', 'success');
    }

    public function reject(string $id): void
    {
        try {
            $report = FinalReport::whereHas('internship', fn($q) =>
                $q->where('supervisor_id', auth()->id())
            )->with(['intern.internProfile'])->findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->toast('Laporan tidak ditemukan.', 'error');
            return;
        }

        if ($report->supervisor_approval !== 'pending') {
            $this->toast('Laporan ini sudah direview.', 'error');
            return;
        }

        $report->update([
            'supervisor_approval' => 'rejected',
        ]);

        SendReportNotificationJob::dispatch(
            $this->notificationService->sendReportRejected($report)
        );

        $this->toast('Laporan akhir ditolak.', 'success');
    }

    public function render()
    {
        $supervisorId = auth()->id();

        $reports = FinalReport::whereHas('internship', fn($q) =>
            $q->where('supervisor_id', $supervisorId)
        )
            ->with(['intern.internProfile', 'internship.vacancy'])
            ->when($this->filterStatus, fn($q) => $q->where('supervisor_approval', $this->filterStatus))
            ->latest('submitted_at')
            ->paginate(10);

        return view('livewire.supervisor.report-review', compact('reports'));
    }
}
