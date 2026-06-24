<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Internship;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CertificateService
{
    public function issue(Internship $internship, string $issuedBy): Certificate
    {
        if ($internship->status !== 'completed') {
            throw new \Exception('Sertifikat hanya bisa diterbitkan untuk magang yang sudah selesai.');
        }

        if (!$internship->relationLoaded('evaluation')) {
            $internship->load('evaluation');
        }

        if (!$internship->evaluation) {
            throw new \Exception('Penilaian belum diisi oleh pembimbing.');
        }

        if ($internship->certificate) {
            throw new \Exception('Sertifikat sudah diterbitkan.');
        }

        return DB::transaction(function () use ($internship, $issuedBy) {
            $count = Certificate::whereYear('created_at', now()->year)->lockForUpdate()->count();
            $number = sprintf('CERT/TELKOM-SKB/%s/%03d', now()->year, $count + 1);

            $internship->evaluation->evaluated_at = now();
            $internship->evaluation->save();

            $qrToken = Str::random(64);

            return Certificate::create([
                'internship_id' => $internship->id,
                'intern_id' => $internship->intern_id,
                'certificate_number' => $number,
                'issued_by' => $issuedBy,
                'final_score' => $internship->evaluation->final_score,
                'grade' => $internship->evaluation->grade,
                'qr_code_token' => $qrToken,
                'qr_code_url' => route('public.verify', $qrToken),
                'issued_at' => now(),
            ]);
        });
    }

    public function verify(string $token): ?Certificate
    {
        return Certificate::where('qr_code_token', $token)
            ->with([
                'intern.internProfile',
                'internship.vacancy',
            ])
            ->first();
    }
}
