<?php

namespace Tests\Feature\Livewire\Intern;

use App\Livewire\Intern\FinalReportForm;
use App\Models\FinalReport;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Tests\TestCase;

class FinalReportFormTest extends TestCase
{
    public function test_mount_without_active_internship(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(FinalReportForm::class)
            ->assertSet('hasActiveInternship', false);
    }

    public function test_mount_with_active_internship(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        Internship::factory()->active()->create(['intern_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(FinalReportForm::class)
            ->assertSet('hasActiveInternship', true)
            ->assertSet('canUpload', true);
    }

    public function test_can_upload_report(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);

        $file = UploadedFile::fake()->create('report.pdf', 100);

        Livewire::actingAs($intern)
            ->test(FinalReportForm::class)
            ->set('title', 'Final Report')
            ->set('file', $file)
            ->call('save');

        $this->assertDatabaseHas('final_reports', [
            'internship_id' => $internship->id,
            'title' => 'Final Report',
            'supervisor_approval' => 'pending',
        ]);
    }

    public function test_cannot_upload_without_file(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        Internship::factory()->active()->create(['intern_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(FinalReportForm::class)
            ->set('title', 'Final Report')
            ->call('save')
            ->assertHasErrors(['file']);
    }

    public function test_can_reupload_after_rejection(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);
        FinalReport::factory()->rejected()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($intern)
            ->test(FinalReportForm::class)
            ->assertSet('canUpload', true);
    }
}
