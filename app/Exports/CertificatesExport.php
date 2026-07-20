<?php

namespace App\Exports;

use App\Models\Certificate;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CertificatesExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading
{
    public function query()
    {
        return Certificate::with(['intern.internProfile', 'internship.vacancy', 'issuedBy']);
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function headings(): array
    {
        return [
            'Nomor Sertifikat',
            'Peserta',
            'Posisi',
            'Nilai Akhir',
            'Grade',
            'Diterbitkan Oleh',
            'Tanggal Terbit',
        ];
    }

    public function map($certificate): array
    {
        return [
            $certificate->certificate_number,
            $certificate->intern?->displayName(),
            $certificate->internship?->vacancy?->title ?? '-',
            $certificate->final_score,
            $certificate->grade,
            $certificate->issuedBy?->supervisorProfile?->full_name ?? $certificate->issuedBy?->email ?? '-',
            $certificate->issued_at?->format('Y-m-d') ?? '-',
        ];
    }
}
