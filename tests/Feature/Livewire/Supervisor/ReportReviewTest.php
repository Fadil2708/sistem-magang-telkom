<?php

namespace Tests\Feature\Livewire\Supervisor;

use App\Livewire\Supervisor\ReportReview;
use App\Models\FinalReport;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class ReportReviewTest extends TestCase
{
    public function test_can_approve_report(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $report = FinalReport::factory()->pending()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(ReportReview::class)
            ->call('approve', $report->id);

        $this->assertEquals('approved', $report->fresh()->supervisor_approval);
        $this->assertNotNull($report->fresh()->approved_at);
    }

    public function test_can_reject_report(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $report = FinalReport::factory()->pending()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(ReportReview::class)
            ->call('reject', $report->id);

        $this->assertEquals('rejected', $report->fresh()->supervisor_approval);
    }

    public function test_cannot_approve_already_reviewed_report(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $report = FinalReport::factory()->approved()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(ReportReview::class)
            ->call('approve', $report->id);

        $this->assertEquals('approved', $report->fresh()->supervisor_approval);
    }

    public function test_approve_other_supervisor_report_fails_gracefully(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $otherSupervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $otherSupervisor->id,
        ]);
        $report = FinalReport::factory()->pending()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(ReportReview::class)
            ->call('approve', $report->id);
    }
}
