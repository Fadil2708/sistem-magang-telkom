<?php

namespace App\Services;

use App\Exceptions\IncompleteProfileException;
use App\Exceptions\QuotaFullException;
use App\Models\Application;
use App\Models\Internship;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApplicationService
{
    private const TRANSITIONS = [
        'submitted'           => ['under_review'],
        'under_review'        => ['interview_scheduled', 'rejected'],
        'interview_scheduled' => ['accepted', 'rejected'],
        'accepted'            => [],
        'rejected'            => ['under_review'],
    ];

    private const ACTIVE_STATUSES = ['submitted', 'under_review', 'interview_scheduled'];
    private const MAX_ACTIVE_APPLICATIONS = 2;

    public function apply(User $intern, string $vacancyId): Application
    {
        $vacancy = Vacancy::findOrFail($vacancyId);

        $this->ensureProfileComplete($intern);
        $this->ensureVacancyOpen($vacancy);
        $this->ensureNotDuplicate($intern, $vacancy);
        $this->ensureQuotaAvailable($vacancy);
        $this->ensureActiveApplicationLimit($intern);

        $application = Application::create([
            'intern_id'  => $intern->id,
            'vacancy_id' => $vacancy->id,
            'status'     => 'submitted',
            'applied_at' => now(),
        ]);

        return $application;
    }

    public function updateStatus(Application $application, string $newStatus, ?string $reason = null, ?string $interviewDate = null): void
    {
        $this->validateTransition($application, $newStatus);

        $data = ['status' => $newStatus];

        if ($newStatus === 'rejected') {
            if (empty($reason)) {
                throw new \Exception('Alasan penolakan wajib diisi.');
            }
            $data['rejection_reason'] = $reason;
        }

        if ($newStatus === 'interview_scheduled' && $interviewDate) {
            $data['interview_date'] = $interviewDate;
        }

        $application->update($data);
    }

    public function accept(Application $application): Internship
    {
        $this->validateTransition($application, 'accepted');

        $this->ensureQuotaAvailable($application->vacancy);

        return DB::transaction(function () use ($application) {
            $application->update(['status' => 'accepted']);

            return Internship::create([
                'application_id'    => $application->id,
                'intern_id'         => $application->intern_id,
                'vacancy_id'        => $application->vacancy_id,
                'supervisor_id'     => null,
                'actual_start_date' => $application->vacancy->start_date,
                'actual_end_date'   => $application->vacancy->end_date,
                'status'            => 'active',
            ]);
        });
    }

    public function reject(Application $application, string $reason): void
    {
        $this->validateTransition($application, 'rejected');

        if (empty($reason)) {
            throw new \Exception('Alasan penolakan wajib diisi.');
        }

        $application->update([
            'status'           => 'rejected',
            'rejection_reason' => $reason,
        ]);
    }

    public function cancel(Application $application): void
    {
        if ($application->status !== 'submitted') {
            throw new \Exception('Hanya lamaran dengan status submitted yang dapat dibatalkan.');
        }

        $application->update(['status' => 'cancelled']);
    }

    private function validateTransition(Application $application, string $targetStatus): void
    {
        $current = $application->status;
        $allowed = self::TRANSITIONS[$current] ?? [];

        if (!in_array($targetStatus, $allowed, true)) {
            throw new \Exception(
                "Tidak bisa mengubah status dari {$current} ke {$targetStatus}."
            );
        }
    }

    private function ensureProfileComplete(User $intern): void
    {
        $profile = $intern->internProfile;
        $required = ['full_name', 'institution_name', 'major', 'student_id', 'cv_url'];

        foreach ($required as $field) {
            if (!$profile || empty($profile->{$field})) {
                throw new IncompleteProfileException($field);
            }
        }
    }

    private function ensureVacancyOpen(Vacancy $vacancy): void
    {
        if ($vacancy->status !== 'open') {
            throw new \Exception('Lowongan tidak tersedia.');
        }
    }

    private function ensureNotDuplicate(User $intern, Vacancy $vacancy): void
    {
        $exists = Application::where('intern_id', $intern->id)
            ->where('vacancy_id', $vacancy->id)
            ->exists();

        if ($exists) {
            throw new \Exception('Anda sudah pernah melamar lowongan ini.');
        }
    }

    private function ensureQuotaAvailable(Vacancy $vacancy): void
    {
        $acceptedCount = Application::where('vacancy_id', $vacancy->id)
            ->where('status', 'accepted')
            ->count();

        if ($acceptedCount >= $vacancy->quota) {
            throw new QuotaFullException();
        }
    }

    private function ensureActiveApplicationLimit(User $intern): void
    {
        $activeCount = Application::where('intern_id', $intern->id)
            ->whereIn('status', self::ACTIVE_STATUSES)
            ->count();

        if ($activeCount >= self::MAX_ACTIVE_APPLICATIONS) {
            throw new \Exception('Anda hanya dapat memiliki maksimal 2 lamaran aktif. Silakan tunggu hingga salah satu lamaran selesai diproses sebelum melamar lagi.');
        }
    }
}
