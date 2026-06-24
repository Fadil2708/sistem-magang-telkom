<?php

namespace Tests\Feature\Http;

use App\Models\FinalReport;
use App\Models\Internship;
use App\Models\User;
use Tests\TestCase;

class ReportAccessTest extends TestCase
{
    public function test_intern_can_view_own_report(): void
    {
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);
        FinalReport::factory()->pending()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        $response = $this->actingAs($intern)->getJson('/api/v1/internships/' . $internship->id . '/reports');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);
    }

    public function test_intern_cannot_view_others_report(): void
    {
        $intern = User::factory()->intern()->create();
        $other = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create(['intern_id' => $other->id]);
        FinalReport::factory()->pending()->create([
            'internship_id' => $internship->id,
            'intern_id' => $other->id,
        ]);

        $response = $this->actingAs($intern)->getJson('/api/v1/internships/' . $internship->id . '/reports');

        $response->assertStatus(403);
    }

    public function test_supervisor_can_view_own_intern_report(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        FinalReport::factory()->pending()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        $response = $this->actingAs($supervisor)->getJson('/api/v1/internships/' . $internship->id . '/reports');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_any_report(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);
        FinalReport::factory()->pending()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        $response = $this->actingAs($admin)->getJson('/api/v1/internships/' . $internship->id . '/reports');

        $response->assertStatus(200);
    }
}
