<?php

namespace Tests\Unit\Services;

use App\Services\FileUploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadServiceTest extends TestCase
{
    private FileUploadService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new FileUploadService();
        Storage::fake('private');
    }

    public function test_upload_profile_photo_returns_path(): void
    {
        $file = UploadedFile::fake()->image('photo.jpg');

        $path = $this->service->uploadProfilePhoto($file, 'user-1');

        $this->assertNotNull($path);
        $this->assertStringContainsString('interns/user-1/photo', $path);
        Storage::disk('private')->assertExists($path);
    }

    public function test_upload_cv_returns_path(): void
    {
        $file = UploadedFile::fake()->create('cv.pdf', 100);

        $path = $this->service->uploadCv($file, 'user-1');

        $this->assertNotNull($path);
        $this->assertStringContainsString('interns/user-1/cv', $path);
        Storage::disk('private')->assertExists($path);
    }

    public function test_upload_cover_letter_returns_path(): void
    {
        $file = UploadedFile::fake()->create('cover.pdf', 100);

        $path = $this->service->uploadCoverLetter($file, 'user-1');

        $this->assertNotNull($path);
        $this->assertStringContainsString('interns/user-1/cover-letter', $path);
        Storage::disk('private')->assertExists($path);
    }

    public function test_upload_final_report_returns_path(): void
    {
        $file = UploadedFile::fake()->create('report.pdf', 100);

        $path = $this->service->uploadFinalReport($file, 'internship-1');

        $this->assertNotNull($path);
        $this->assertStringContainsString('reports/internship-1', $path);
        Storage::disk('private')->assertExists($path);
    }

    public function test_upload_certificate_returns_path(): void
    {
        $file = UploadedFile::fake()->create('cert.pdf', 100);

        $path = $this->service->uploadCertificate($file, 'internship-1');

        $this->assertNotNull($path);
        $this->assertStringContainsString('certificates/internship-1', $path);
        Storage::disk('private')->assertExists($path);
    }

    public function test_delete_existing_file_returns_true(): void
    {
        $file = UploadedFile::fake()->create('test.pdf', 100);
        $path = $file->store('test', 'private');

        $result = $this->service->delete($path);

        $this->assertTrue($result);
        Storage::disk('private')->assertMissing($path);
    }

    public function test_delete_non_existent_file_returns_false(): void
    {
        $result = $this->service->delete('non/existent/path.pdf');

        $this->assertFalse($result);
    }

    public function test_delete_with_null_path_returns_false(): void
    {
        $result = $this->service->delete('');

        $this->assertFalse($result);
    }
}
