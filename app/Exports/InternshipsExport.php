<?php

namespace App\Exports;

use App\Models\Internship;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InternshipsExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading
{
    public function query()
    {
        return Internship::with(['intern.internProfile', 'supervisor.supervisorProfile', 'vacancy']);
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function headings(): array
    {
        return [
            'Peserta',
            'Pembimbing',
            'Posisi',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Status',
        ];
    }

    public function map($internship): array
    {
        return [
            $internship->intern?->displayName(),
            $internship->supervisor?->supervisorProfile?->full_name ?? '-',
            $internship->vacancy?->title ?? '-',
            $internship->actual_start_date?->format('Y-m-d') ?? '-',
            $internship->actual_end_date?->format('Y-m-d') ?? '-',
            $internship->status,
        ];
    }
}
