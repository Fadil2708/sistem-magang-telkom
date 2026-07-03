<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    private const ALLOWED_MIMES = [
        'application/pdf',
        'image/jpeg',
        'image/png',
    ];

    private const ALLOWED_EXTENSIONS = [
        'pdf',
        'jpg',
        'jpeg',
        'png',
    ];

    private function validateFile(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            throw new \InvalidArgumentException("File extension .{$extension} is not allowed.");
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $detectedMime = finfo_file($finfo, $file->getPathname());
        finfo_close($finfo);

        $genericMimes = ['application/octet-stream', 'application/x-empty', 'inode/x-empty', ''];
        if ($detectedMime === false || in_array($detectedMime, $genericMimes, true)) {
            $detectedMime = $file->getMimeType();
        }

        if (!in_array($detectedMime, self::ALLOWED_MIMES, true)) {
            throw new \InvalidArgumentException("File type {$detectedMime} is not allowed.");
        }
    }

    private function storeFile(UploadedFile $file, string $path, string $context): ?string
    {
        try {
            $this->validateFile($file);
            return $file->store($path, 'private');
        } catch (\Throwable $e) {
            Log::error("[FileUpload] {$context} failed: {$e->getMessage()}");
            return null;
        }
    }

    public function uploadProfilePhoto(UploadedFile $file, string $userId): ?string
    {
        return $this->storeFile($file, "interns/{$userId}/photo", 'Profile photo');
    }

    public function uploadCv(UploadedFile $file, string $userId): ?string
    {
        return $this->storeFile($file, "interns/{$userId}/cv", 'CV');
    }

    public function uploadCoverLetter(UploadedFile $file, string $userId): ?string
    {
        return $this->storeFile($file, "interns/{$userId}/cover-letter", 'Cover letter');
    }

    public function uploadFinalReport(UploadedFile $file, string $internshipId): ?string
    {
        return $this->storeFile($file, "reports/{$internshipId}", 'Final report');
    }

    public function uploadCertificate(UploadedFile $file, string $internshipId): ?string
    {
        return $this->storeFile($file, "certificates/{$internshipId}", 'Certificate');
    }

    public function delete(string $path): bool
    {
        try {
            if ($path && Storage::disk('private')->exists($path)) {
                return Storage::disk('private')->delete($path);
            }
        } catch (\Throwable $e) {
            Log::error("[FileUpload] Delete failed for path {$path}: {$e->getMessage()}");
        }
        return false;
    }
}
