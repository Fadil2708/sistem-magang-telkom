<?php

namespace App\Exports;

use App\Models\Logbook;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LogbooksExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Logbook::with(['intern.internProfile', 'internship.vacancy']);
    }

    public function headings(): array
    {
        return [
            'Peserta',
            'Posisi',
            'Tanggal Aktivitas',
            'Kegiatan',
            'Hasil',
            'Status Validasi',
            'Tanggal Review',
        ];
    }

    public function map($logbook): array
    {
        return [
            $logbook->intern?->internProfile?->full_name ?? $logbook->intern?->email,
            $logbook->internship?->vacancy?->title ?? '-',
            $logbook->activity_date?->format('Y-m-d') ?? '-',
            $logbook->activities,
            $logbook->output,
            $logbook->validation_status,
            $logbook->reviewed_at?->format('Y-m-d H:i') ?? '-',
        ];
    }
}
