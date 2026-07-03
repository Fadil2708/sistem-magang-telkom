<?php

namespace App\Exports;

use App\Models\Evaluation;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EvaluationsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Evaluation::with(['internship.intern.internProfile', 'internship.vacancy', 'supervisor.supervisorProfile']);
    }

    public function headings(): array
    {
        return [
            'Peserta',
            'Pembimbing',
            'Posisi',
            'Soft Skill',
            'Hard Skill',
            'Kehadiran',
            'Sikap',
            'Nilai Akhir',
            'Grade',
            'Tanggal Evaluasi',
        ];
    }

    public function map($evaluation): array
    {
        return [
            $evaluation->internship?->intern?->displayName() ?? '-',
            $evaluation->supervisor?->supervisorProfile?->full_name ?? '-',
            $evaluation->internship?->vacancy?->title ?? '-',
            $evaluation->soft_skill_score,
            $evaluation->hard_skill_score,
            $evaluation->attendance_score,
            $evaluation->attitude_score,
            $evaluation->final_score,
            $evaluation->grade,
            $evaluation->evaluated_at?->format('Y-m-d') ?? '-',
        ];
    }
}
