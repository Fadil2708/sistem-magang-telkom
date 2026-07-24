<?php

namespace App\Services;

use App\Models\Internship;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class InternshipService
{
    public function getAdminPaginatedList(string $filterStatus = ''): LengthAwarePaginator
    {
        return Internship::with(['intern.internProfile', 'supervisor.supervisorProfile', 'vacancy', 'evaluation'])
            ->when($filterStatus, fn($q) => $q->where('status', $filterStatus))
            ->latest()
            ->paginate(10);
    }

    public function updateStatus(string $id, string $status): Internship
    {
        $internship = Internship::findOrFail($id);
        $internship->update(['status' => $status]);
        return $internship->fresh();
    }

    public function updateDates(string $id, ?string $startDate, ?string $endDate): Internship
    {
        $internship = Internship::findOrFail($id);
        $data = [];
        if ($startDate) $data['actual_start_date'] = $startDate;
        if ($endDate) $data['actual_end_date'] = $endDate;
        $internship->update($data);
        return $internship->fresh();
    }

    public function assignSupervisor(string $internshipId, string $supervisorId): Internship
    {
        $internship = Internship::findOrFail($internshipId);
        $internship->update(['supervisor_id' => $supervisorId]);
        return $internship->fresh();
    }

    public function getSupervisorInterns(string $supervisorId, string $filterStatus = 'active'): LengthAwarePaginator
    {
        return Internship::with(['intern.internProfile', 'vacancy'])
            ->where('supervisor_id', $supervisorId)
            ->when($filterStatus, fn($q) => $q->where('status', $filterStatus))
            ->latest()
            ->paginate(10);
    }

    public function getSupervisorMappedList(string $filterStatus = 'active'): LengthAwarePaginator
    {
        return Internship::with(['intern.internProfile', 'vacancy'])
            ->whereNull('supervisor_id')
            ->when($filterStatus, fn($q) => $q->where('status', $filterStatus))
            ->latest()
            ->paginate(10);
    }

    public function getSupervisors(): \Illuminate\Support\Collection
    {
        return User::where('role', 'supervisor')
            ->where('is_active', true)
            ->with('supervisorProfile')
            ->orderBy('email')
            ->get();
    }
}