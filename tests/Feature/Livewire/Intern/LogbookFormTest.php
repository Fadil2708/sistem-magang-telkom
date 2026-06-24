<?php

namespace Tests\Feature\Livewire\Intern;

use App\Livewire\Intern\LogbookForm;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\Logbook;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class LogbookFormTest extends TestCase
{
    public function test_mount_without_active_internship(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(LogbookForm::class)
            ->assertSet('hasActiveInternship', false);
    }

    public function test_mount_with_active_internship(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        Internship::factory()->active()->create(['intern_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(LogbookForm::class)
            ->assertSet('hasActiveInternship', true);
    }

    public function test_can_create_logbook(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(LogbookForm::class)
            ->set('activity_date', now()->format('Y-m-d'))
            ->set('activities', 'Worked on project features for the internship program.')
            ->set('output', 'Completed module')
            ->call('save');

        $this->assertDatabaseHas('logbooks', [
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
            'validation_status' => 'draft',
        ]);
    }

    public function test_can_edit_logbook(): void
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
            ->test(LogbookForm::class, ['id' => $logbook->id])
            ->set('activities', 'Updated activities for the logbook entry.')
            ->set('output', 'Updated output')
            ->call('save');

        $this->assertEquals('Updated activities for the logbook entry.', $logbook->fresh()->activities);
    }

    public function test_validation_fails_without_activities(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        Internship::factory()->active()->create(['intern_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(LogbookForm::class)
            ->set('activity_date', now()->format('Y-m-d'))
            ->set('activities', 'Short')
            ->set('output', 'Output')
            ->call('save')
            ->assertHasErrors(['activities']);
    }

    public function test_cannot_create_duplicate_date(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);

        $date = now()->format('Y-m-d');

        $logbook = \App\Models\Logbook::create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
            'activity_date' => $date,
            'activities' => 'Existing logbook for the day.',
            'output' => 'Existing output',
            'validation_status' => 'draft',
        ]);

        $this->assertNotNull($logbook->id, 'Logbook should have been created');
        $this->assertEquals($internship->id, $logbook->internship_id, 'Logbook internship_id should match');

        $allLogbooks = \App\Models\Logbook::where('internship_id', $internship->id)->get();
        $this->assertEquals(1, $allLogbooks->count(), 'One logbook should exist');

        Livewire::actingAs($intern)
            ->test(LogbookForm::class)
            ->set('activity_date', $date)
            ->set('activities', 'Worked on project features for the internship program.')
            ->set('output', 'Completed module')
            ->call('save');

        $count = \App\Models\Logbook::where('internship_id', $internship->id)->count();
        $this->assertEquals(1, $count, 'Duplicate logbook should not be created.');
    }

    public function test_validation_errors_are_detected(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        Internship::factory()->active()->create(['intern_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(LogbookForm::class)
            ->set('activity_date', '')
            ->set('activities', 'Short')
            ->set('output', '')
            ->call('save')
            ->assertHasErrors(['activity_date', 'activities', 'output']);
    }
}
