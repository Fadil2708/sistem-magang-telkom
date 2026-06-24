<?php

namespace App\Jobs;

use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateCertificatePdfJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public $tries = 3;

    public function __construct(
        private readonly Certificate $certificate
    ) {}

    public function uniqueId(): string
    {
        return $this->certificate->id;
    }

    public function handle(): void
    {
        $this->certificate->load(['intern.internProfile', 'internship.vacancy', 'internship.supervisor.supervisorProfile']);

        $qrCodeSvg = QrCode::format('svg')
            ->size(120)
            ->margin(1)
            ->generate($this->certificate->qr_code_url);

        $pdf = Pdf::loadView('certificates.template', [
            'certificate' => $this->certificate,
            'qrCode' => $qrCodeSvg,
        ]);

        $path = "certificates/{$this->certificate->internship_id}/certificate.pdf";

        Storage::disk('private')->put($path, $pdf->output());

        $this->certificate->update(['certificate_file_url' => $path]);
    }

    public function failed(?\Throwable $exception): void
    {
        Log::error("[GenerateCertificatePdfJob] Certificate {$this->certificate->id} PDF generation failed: {$exception->getMessage()}");
    }
}
