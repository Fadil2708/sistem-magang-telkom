<?php

namespace Tests\Feature\Http;

use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\User;
use Tests\TestCase;

class LogbookFlowTest extends TestCase
{
    public function test_intern_can_create_logbook(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);

        $response = $this->actingAs($intern)->postJson('/api/v1/internships/' . $internship->id . '/logbooks', [
            'activity_date' => now()->format('Y-m-d'),
            'activities' => 'Worked on testing the new feature implementation and fixed several bugs',
            'output' => 'Test plan created and bugs documented',
        ]);

        $response->assertStatus(201);
    }

    public function test_intern_can_submit_logbook(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);
        $logbook = \App\Models\Logbook::factory()->draft()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        $response = $this->actingAs($intern)->patchJson('/api/v1/logbooks/' . $logbook->id . '/submit');

        $response->assertStatus(200);
        $this->assertEquals('submitted', $logbook->fresh()->validation_status);
    }

    public function test_supervisor_can_approve_logbook(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $logbook = \App\Models\Logbook::factory()->submitted()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        $response = $this->actingAs($supervisor)->patchJson('/api/v1/logbooks/' . $logbook->id . '/review', [
            'action' => 'approved',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('approved', $logbook->fresh()->validation_status);
    }

    public function test_supervisor_can_request_revision(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $logbook = \App\Models\Logbook::factory()->submitted()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        $response = $this->actingAs($supervisor)->patchJson('/api/v1/logbooks/' . $logbook->id . '/review', [
            'action' => 'revision_requested',
            'supervisor_notes' => 'Please add more details to the activities section',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('revision_requested', $logbook->fresh()->validation_status);
        $this->assertEquals('Please add more details to the activities section', $logbook->fresh()->supervisor_notes);
    }

    public function test_intern_cannot_submit_other_interns_logbook(): void
    {
        $intern = User::factory()->intern()->create();
        $otherIntern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $otherIntern->id]);
        $internship = Internship::factory()->active()->create(['intern_id' => $otherIntern->id]);
        $logbook = \App\Models\Logbook::factory()->draft()->create([
            'internship_id' => $internship->id,
            'intern_id' => $otherIntern->id,
        ]);

        $response = $this->actingAs($intern)->patchJson('/api/v1/logbooks/' . $logbook->id . '/submit');

        $response->assertStatus(422);
    }
}
