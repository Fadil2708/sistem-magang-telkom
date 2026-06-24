<?php

namespace Tests\Feature\Livewire\Supervisor;

use App\Livewire\Supervisor\LogbookReview;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\Logbook;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class LogbookReviewTest extends TestCase
{
    public function test_approve_changes_status(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $logbook = Logbook::factory()->submitted()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(LogbookReview::class)
            ->call('approve', $logbook->id);

        $this->assertEquals('approved', $logbook->fresh()->validation_status);
    }

    public function test_approve_not_own_logbook_fails_gracefully(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $otherSupervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $otherSupervisor->id,
        ]);
        $logbook = Logbook::factory()->submitted()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(LogbookReview::class)
            ->call('approve', $logbook->id);
    }

    public function test_open_revision_modal(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $logbook = Logbook::factory()->submitted()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(LogbookReview::class)
            ->call('openRevision', $logbook->id)
            ->assertSet('logbookId', $logbook->id)
            ->assertSet('showRevisionModal', true);
    }

    public function test_request_revision_success(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $logbook = Logbook::factory()->submitted()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(LogbookReview::class)
            ->call('openRevision', $logbook->id)
            ->set('revisionNotes', 'Please add more detail')
            ->call('requestRevision');

        $this->assertEquals('revision_requested', $logbook->fresh()->validation_status);
        $this->assertEquals('Please add more detail', $logbook->fresh()->supervisor_notes);
    }

    public function test_request_revision_empty_notes_fails(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $logbook = Logbook::factory()->submitted()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(LogbookReview::class)
            ->call('openRevision', $logbook->id)
            ->set('revisionNotes', '')
            ->call('requestRevision')
            ->assertHasErrors(['revisionNotes']);
    }

    public function test_bulk_approve_approves_multiple_selected_logbooks(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);

        $logbook1 = Logbook::factory()->submitted()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);
        $logbook2 = Logbook::factory()->submitted()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);
        $logbook3 = Logbook::factory()->submitted()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(LogbookReview::class)
            ->set('selectedLogbooks', [$logbook1->id, $logbook3->id])
            ->call('bulkApprove');

        $this->assertEquals('approved', $logbook1->fresh()->validation_status);
        $this->assertEquals('submitted', $logbook2->fresh()->validation_status);
        $this->assertEquals('approved', $logbook3->fresh()->validation_status);
    }

    public function test_bulk_approve_empty_selection_does_not_modify_logbooks(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $logbook = Logbook::factory()->submitted()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(LogbookReview::class)
            ->call('bulkApprove');

        $this->assertEquals('submitted', $logbook->fresh()->validation_status);
    }

    public function test_toggle_select_all_selects_all_submitted_logbooks_across_pages(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);

        $ids = [];
        for ($i = 0; $i < 15; $i++) {
            $logbook = Logbook::factory()->submitted()->create([
                'internship_id' => $internship->id,
                'intern_id' => $intern->id,
            ]);
            $ids[] = $logbook->id;
        }

        $result = Livewire::actingAs($supervisor)
            ->test(LogbookReview::class)
            ->call('toggleSelectAll')
            ->get('selectedLogbooks');

        sort($ids);
        sort($result);

        $this->assertEquals($ids, $result);
    }

    public function test_toggle_select_all_deselects_when_already_all_selected(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);

        $logbook = Logbook::factory()->submitted()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(LogbookReview::class)
            ->call('toggleSelectAll')
            ->assertSet('selectedLogbooks', [$logbook->id])
            ->call('toggleSelectAll')
            ->assertSet('selectedLogbooks', []);
    }

    public function test_bulk_approve_skips_non_submitted_logbooks(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);

        $submitted = Logbook::factory()->submitted()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);
        $approved = Logbook::factory()->approved()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);
        $draft = Logbook::factory()->draft()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(LogbookReview::class)
            ->set('selectedLogbooks', [$submitted->id, $approved->id, $draft->id])
            ->call('bulkApprove');

        $this->assertEquals('approved', $submitted->fresh()->validation_status);
        $this->assertEquals('approved', $approved->fresh()->validation_status);
        $this->assertEquals('draft', $draft->fresh()->validation_status);
    }

    public function test_bulk_approve_skips_other_supervisors_logbook(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $otherSupervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);

        $internship1 = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $internship2 = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $otherSupervisor->id,
        ]);

        $ownLogbook = Logbook::factory()->submitted()->create([
            'internship_id' => $internship1->id,
            'intern_id' => $intern->id,
        ]);
        $otherLogbook = Logbook::factory()->submitted()->create([
            'internship_id' => $internship2->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(LogbookReview::class)
            ->set('selectedLogbooks', [$ownLogbook->id, $otherLogbook->id])
            ->call('bulkApprove');

        $this->assertEquals('approved', $ownLogbook->fresh()->validation_status);
        $this->assertEquals('submitted', $otherLogbook->fresh()->validation_status);
    }

    public function test_updating_filter_status_clears_selection(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $logbook = Logbook::factory()->submitted()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(LogbookReview::class)
            ->set('selectedLogbooks', [$logbook->id])
            ->set('filterStatus', 'approved')
            ->assertSet('selectedLogbooks', []);
    }
}
