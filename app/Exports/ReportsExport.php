<?php

namespace App\Exports;

use App\Models\FinalReport;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return FinalReport::with(['intern.internProfile', 'internship.vacancy']);
    }

    public function headings(): array
    {
        return [
            'Peserta',
            'Judul',
            'Posisi',
            'Tanggal Submit',
            'Status',
            'Tanggal Approve',
        ];
    }

    public function map($report): array
    {
        return [
            $report->intern?->internProfile?->full_name ?? $report->intern?->email,
            $report->title,
            $report->internship?->vacancy?->title ?? '-',
            $report->submitted_at?->format('Y-m-d') ?? '-',
            $report->supervisor_approval,
            $report->approved_at?->format('Y-m-d') ?? '-',
        ];
    }
}
