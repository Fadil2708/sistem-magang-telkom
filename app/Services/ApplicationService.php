<?php

namespace App\Services;

use App\Exceptions\IncompleteProfileException;
use App\Exceptions\QuotaFullException;
use App\Models\Application;
use App\Models\Internship;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Support\Facades\DB;

class ApplicationService
{
    public const TRANSITIONS = [
        'submitted'           => ['under_review'],
        'under_review'        => ['interview_scheduled', 'rejected'],
        'interview_scheduled' => ['accepted', 'rejected'],
        'accepted'            => [],
        'rejected'            => ['under_review'],
    ];

    public function apply(User $intern, string $vacancyId): Application
    {
        $vacancy = Vacancy::findOrFail($vacancyId);

        $this->ensureProfileComplete($intern);
        $this->ensureVacancyOpen($vacancy);
        $this->ensureNotDuplicate($intern, $vacancy);
        $this->ensureNoActiveInternship($intern);
        $this->ensureActiveApplicationLimit($intern);

        return DB::transaction(function () use ($intern, $vacancy) {
            $this->ensureQuotaAvailable($vacancy, lock: true);

            return Application::create([
                'intern_id'  => $intern->id,
                'vacancy_id' => $vacancy->id,
                'status'     => 'submitted',
                'applied_at' => now(),
            ]);
        });
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

        return DB::transaction(function () use ($application) {
            $this->ensureQuotaAvailable($application->vacancy, lock: true);

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
        $this->updateStatus($application, 'rejected', $reason);
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
        $required = \App\Models\InternProfile::requiredFields();

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

    private function ensureQuotaAvailable(Vacancy $vacancy, bool $lock = false): void
    {
        $query = Application::where('vacancy_id', $vacancy->id)
            ->where('status', 'accepted');

        if ($lock) {
            $query->lockForUpdate();
        }

        $acceptedCount = $query->count();

        if ($acceptedCount >= $vacancy->quota) {
            throw new QuotaFullException();
        }
    }

    private function ensureNoActiveInternship(User $intern): void
    {
        $hasActive = Internship::where('intern_id', $intern->id)
            ->whereIn('status', ['active', 'extended'])
            ->exists();

        if ($hasActive) {
            throw new \Exception('Anda masih memiliki magang aktif. Tidak dapat melamar lowongan baru.');
        }
    }

    private function ensureActiveApplicationLimit(User $intern): void
    {
        $activeCount = Application::where('intern_id', $intern->id)
            ->whereIn('status', config('app.application.active_statuses'))
            ->count();

        if ($activeCount >= config('app.application.max_active')) {
            throw new \Exception('Anda hanya dapat memiliki maksimal 2 lamaran aktif. Silakan tunggu hingga salah satu lamaran selesai diproses sebelum melamar lagi.');
        }
    }
}
