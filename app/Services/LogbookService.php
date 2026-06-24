<?php

namespace App\Services;

use App\Models\Internship;
use App\Models\Logbook;
use App\Models\User;
use Illuminate\Support\Str;

class LogbookService
{
    private const TRANSITIONS = [
        'draft'              => ['submitted'],
        'submitted'          => ['approved', 'revision_requested'],
        'revision_requested' => ['submitted'],
        'approved'           => [],
    ];

    public function create(string $internshipId, User $intern, array $data): Logbook
    {
        $internship = Internship::where('intern_id', $intern->id)
            ->where('status', 'active')
            ->findOrFail($internshipId);

        $this->ensureNoDuplicateDate($internshipId, $data['activity_date']);

        return Logbook::create([
            'id' => (string) Str::uuid(),
            'internship_id' => $internshipId,
            'intern_id' => $intern->id,
            'activity_date' => $data['activity_date'],
            'activities' => $data['activities'],
            'output' => $data['output'],
            'validation_status' => 'draft',
        ]);
    }

    public function update(Logbook $logbook, User $intern, array $data): Logbook
    {
        if ($logbook->intern_id !== $intern->id) {
            throw new \Exception('Anda tidak berhak mengubah logbook ini.');
        }

        if (!in_array($logbook->validation_status, ['draft', 'revision_requested'], true)) {
            throw new \Exception('Logbook hanya bisa diedit saat status draft atau revisi.');
        }

        $logbook->update($data);

        return $logbook->fresh();
    }

    public function submit(Logbook $logbook, User $intern): Logbook
    {
        if ($logbook->intern_id !== $intern->id) {
            throw new \Exception('Anda tidak berhak mengirim logbook ini.');
        }

        $this->validateTransition($logbook, 'submitted');

        $logbook->update(['validation_status' => 'submitted']);

        return $logbook->fresh();
    }

    public function review(Logbook $logbook, User $supervisor, string $action, ?string $notes = null): Logbook
    {
        $internship = $logbook->internship;

        if ($internship->supervisor_id === null || $internship->supervisor_id !== $supervisor->id) {
            throw new \Exception('Anda tidak berhak mereview logbook ini.');
        }

        $this->validateTransition($logbook, $action);

        if ($action === 'revision_requested' && empty($notes)) {
            throw new \Exception('Catatan revisi wajib diisi.');
        }

        $logbook->update([
            'validation_status' => $action,
            'supervisor_notes' => $notes,
            'reviewed_at' => now(),
        ]);

        return $logbook->fresh();
    }

    private function ensureNoDuplicateDate(string $internshipId, string $activityDate): void
    {
        $exists = Logbook::where('internship_id', $internshipId)
            ->where('activity_date', $activityDate)
            ->exists();

        if ($exists) {
            throw new \Exception('Anda sudah mengisi logbook untuk tanggal ini.');
        }
    }

    private function validateTransition(Logbook $logbook, string $targetStatus): void
    {
        $current = $logbook->validation_status;
        $allowed = self::TRANSITIONS[$current] ?? [];

        if (!in_array($targetStatus, $allowed, true)) {
            throw new \Exception(
                "Tidak bisa mengubah status logbook dari {$current} ke {$targetStatus}."
            );
        }
    }
}
