<?php

namespace App\Exports;

use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ApplicationsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Application::with(['intern.internProfile', 'vacancy']);
    }

    public function headings(): array
    {
        return [
            'Peserta',
            'Lowongan',
            'Tanggal Daftar',
            'Status',
            'Tanggal Wawancara',
        ];
    }

    public function map($application): array
    {
        return [
            $application->intern?->internProfile?->full_name ?? $application->intern?->email,
            $application->vacancy?->title ?? '-',
            $application->applied_at?->format('Y-m-d') ?? '-',
            $application->status,
            $application->interview_date?->format('Y-m-d H:i') ?? '-',
        ];
    }
}
