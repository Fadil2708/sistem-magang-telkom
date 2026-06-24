<?php

namespace Tests\Feature\Http;

use App\Models\Application;
use App\Models\Certificate;
use App\Models\FinalReport;
use App\Models\Internship;
use App\Models\Logbook;
use App\Models\User;
use App\Models\Vacancy;
use Tests\TestCase;

class DashboardStatsTest extends TestCase
{
    public function test_admin_stats(): void
    {
        $admin = User::factory()->admin()->create();
        Internship::factory()->active()->count(3)->create();
        Vacancy::factory()->open()->create();
        Application::factory()->submitted()->create(['intern_id' => User::factory()->intern()->create()->id]);
        Logbook::factory()->submitted()->create([
            'internship_id' => Internship::factory()->active()->create(['intern_id' => User::factory()->intern()->create()->id])->id,
            'intern_id' => User::factory()->intern()->create()->id,
        ]);

        $response = $this->actingAs($admin)->getJson('/api/v1/dashboard/stats');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);
    }

    public function test_supervisor_stats(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        Logbook::factory()->submitted()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);
        FinalReport::factory()->pending()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        $response = $this->actingAs($supervisor)->getJson('/api/v1/dashboard/stats');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_intern_stats(): void
    {
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);
        Application::factory()->submitted()->create(['intern_id' => $intern->id]);
        Logbook::factory()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
            'activity_date' => now(),
        ]);

        $response = $this->actingAs($intern)->getJson('/api/v1/dashboard/stats');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_unauthenticated_cannot_access_stats(): void
    {
        $response = $this->getJson('/api/v1/dashboard/stats');

        $response->assertStatus(401);
    }
}
