<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Certificate;
use App\Models\FinalReport;
use App\Models\Logbook;

class NotificationService
{
    public function sendApplicationSubmitted(Application $application): array
    {
        return [
            'type' => 'application.submitted',
            'recipient' => $application->intern->email,
            'data' => [
                'intern_name' => $application->intern->internProfile?->full_name,
                'vacancy_title' => $application->vacancy->title,
            ],
        ];
    }

    public function sendApplicationStatusUpdated(Application $application): array
    {
        return [
            'type' => 'application.status_updated',
            'recipient' => $application->intern->email,
            'data' => [
                'intern_name' => $application->intern->internProfile?->full_name,
                'vacancy_title' => $application->vacancy->title,
                'status' => $application->status,
            ],
        ];
    }

    public function sendInterviewScheduled(Application $application): array
    {
        return [
            'type' => 'application.interview_scheduled',
            'recipient' => $application->intern->email,
            'data' => [
                'intern_name' => $application->intern->internProfile?->full_name,
                'vacancy_title' => $application->vacancy->title,
                'interview_date' => $application->interview_date?->format('Y-m-d H:i'),
            ],
        ];
    }

    public function sendApplicationDecision(Application $application): array
    {
        $isAccepted = $application->status === 'accepted';

        return [
            'type' => 'application.decision',
            'recipient' => $application->intern->email,
            'data' => [
                'intern_name' => $application->intern->internProfile?->full_name,
                'vacancy_title' => $application->vacancy->title,
                'status' => $application->status,
                'rejection_reason' => $application->rejection_reason,
            ],
        ];
    }

    public function sendLogbookRevisionRequested(Logbook $logbook): array
    {
        return [
            'type' => 'logbook.revision_requested',
            'recipient' => $logbook->intern->email,
            'data' => [
                'intern_name' => $logbook->intern->internProfile?->full_name,
                'activity_date' => $logbook->activity_date?->format('Y-m-d'),
                'supervisor_notes' => $logbook->supervisor_notes,
            ],
        ];
    }

    public function sendLogbookApproved(Logbook $logbook): array
    {
        return [
            'type' => 'logbook.approved',
            'recipient' => $logbook->intern->email,
            'data' => [
                'intern_name' => $logbook->intern->internProfile?->full_name,
                'activity_date' => $logbook->activity_date?->format('Y-m-d'),
            ],
        ];
    }

    public function sendReportRejected(FinalReport $report): array
    {
        return [
            'type' => 'report.rejected',
            'recipient' => $report->intern->email,
            'data' => [
                'intern_name' => $report->intern->internProfile?->full_name,
                'report_title' => $report->title,
            ],
        ];
    }

    public function sendReportApproved(FinalReport $report): array
    {
        return [
            'type' => 'report.approved',
            'recipient' => $report->intern->email,
            'data' => [
                'intern_name' => $report->intern->internProfile?->full_name,
                'report_title' => $report->title,
            ],
        ];
    }

    public function sendCertificateIssued(Certificate $certificate): array
    {
        return [
            'type' => 'certificate.issued',
            'recipient' => $certificate->intern->email,
            'data' => [
                'intern_name' => $certificate->intern->internProfile?->full_name,
                'certificate_number' => $certificate->certificate_number,
            ],
        ];
    }

    public function sendNewLogbookToSupervisor(Logbook $logbook): array
    {
        $supervisor = $logbook->internship?->supervisor;

        return [
            'type' => 'logbook.new_submission',
            'recipient' => $supervisor?->email,
            'data' => [
                'supervisor_name' => $supervisor?->supervisorProfile?->full_name,
                'intern_name' => $logbook->intern->internProfile?->full_name,
                'activity_date' => $logbook->activity_date?->format('Y-m-d'),
            ],
        ];
    }
}
