<?php

namespace Tests\Feature\Livewire\Intern;

use App\Livewire\Intern\LogbookList;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\Logbook;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class LogbookListTest extends TestCase
{
    public function test_mount_without_active_internship(): void
    {
        $intern = User::factory()->intern()->create();

        Livewire::actingAs($intern)
            ->test(LogbookList::class)
            ->assertSet('hasActiveInternship', false);
    }

    public function test_mount_with_active_internship(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        Internship::factory()->active()->create(['intern_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(LogbookList::class)
            ->assertSet('hasActiveInternship', true);
    }

    public function test_can_submit_draft_logbook(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);
        $logbook = Logbook::factory()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
            'validation_status' => 'draft',
        ]);

        Livewire::actingAs($intern)
            ->test(LogbookList::class)
            ->call('submit', $logbook->id);

        $this->assertEquals('submitted', $logbook->fresh()->validation_status);
    }

    public function test_can_delete_draft_logbook(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);
        $logbook = Logbook::factory()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
            'validation_status' => 'draft',
        ]);

        Livewire::actingAs($intern)
            ->test(LogbookList::class)
            ->call('delete', $logbook->id);

        $this->assertDatabaseMissing('logbooks', ['id' => $logbook->id]);
    }

    public function test_cannot_delete_submitted_logbook(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);
        $logbook = Logbook::factory()->submitted()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        Livewire::actingAs($intern)
            ->test(LogbookList::class)
            ->call('delete', $logbook->id);
    }

    public function test_can_filter_by_status(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        Internship::factory()->active()->create(['intern_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(LogbookList::class)
            ->set('filterStatus', 'submitted')
            ->assertSet('filterStatus', 'submitted');
    }
}
