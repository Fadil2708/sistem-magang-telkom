<?php

namespace Tests\Feature\Http;

use App\Models\Internship;
use App\Models\Logbook;
use App\Models\User;
use Tests\TestCase;

class LogbookAccessTest extends TestCase
{
    public function test_intern_can_list_own_logbooks(): void
    {
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);
        Logbook::factory()->count(2)->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        $response = $this->actingAs($intern)->getJson('/api/v1/internships/' . $internship->id . '/logbooks');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_intern_cannot_list_others_logbooks(): void
    {
        $intern = User::factory()->intern()->create();
        $other = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create(['intern_id' => $other->id]);

        $response = $this->actingAs($intern)->getJson('/api/v1/internships/' . $internship->id . '/logbooks');

        $response->assertStatus(403);
    }

    public function test_supervisor_can_list_own_intern_logbooks(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        Logbook::factory()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        $response = $this->actingAs($supervisor)->getJson('/api/v1/internships/' . $internship->id . '/logbooks');

        $response->assertStatus(200);
    }

    public function test_supervisor_cannot_list_other_supervisor_intern_logbooks(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $otherSupervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $otherSupervisor->id,
        ]);

        $response = $this->actingAs($supervisor)->getJson('/api/v1/internships/' . $internship->id . '/logbooks');

        $response->assertStatus(403);
    }

    public function test_intern_can_show_own_logbook(): void
    {
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);
        $logbook = Logbook::factory()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        $response = $this->actingAs($intern)->getJson('/api/v1/logbooks/' . $logbook->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $logbook->id);
    }

    public function test_intern_cannot_show_others_logbook(): void
    {
        $intern = User::factory()->intern()->create();
        $other = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create(['intern_id' => $other->id]);
        $logbook = Logbook::factory()->create([
            'internship_id' => $internship->id,
            'intern_id' => $other->id,
        ]);

        $response = $this->actingAs($intern)->getJson('/api/v1/logbooks/' . $logbook->id);

        $response->assertStatus(403);
    }

    public function test_intern_can_update_own_logbook(): void
    {
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);
        $logbook = Logbook::factory()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
            'validation_status' => 'draft',
        ]);

        $response = $this->actingAs($intern)->putJson('/api/v1/logbooks/' . $logbook->id, [
            'activities' => 'Updated activity description that is long enough to pass validation',
            'output' => 'Output result',
            'activity_date' => now()->format('Y-m-d'),
        ]);

        $response->assertStatus(200);
    }

    public function test_supervisor_cannot_update_intern_logbook(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $logbook = Logbook::factory()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        $response = $this->actingAs($supervisor)->putJson('/api/v1/logbooks/' . $logbook->id, [
            'activities' => 'Should fail description that is long enough to pass validation',
            'output' => 'Should fail',
            'activity_date' => now()->format('Y-m-d'),
        ]);

        $response->assertStatus(403);
    }
}
