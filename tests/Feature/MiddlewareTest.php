<?php

namespace Tests\Feature;

use App\Models\InternProfile;
use App\Models\User;
use Tests\TestCase;

class MiddlewareTest extends TestCase
{
    // ─── EnsureRole Middleware ───────────────────────────

    public function test_admin_can_access_admin_route(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    public function test_intern_cannot_access_admin_route(): void
    {
        $intern = User::factory()->intern()->create();

        $response = $this->actingAs($intern)->get('/admin/dashboard');

        $response->assertRedirect();
    }

    public function test_supervisor_cannot_access_admin_route(): void
    {
        $supervisor = User::factory()->supervisor()->create();

        $response = $this->actingAs($supervisor)->get('/admin/dashboard');

        $response->assertRedirect();
    }

    public function test_intern_can_access_intern_route(): void
    {
        $intern = User::factory()->intern()->create();

        $response = $this->actingAs($intern)->get('/intern/dashboard');

        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_intern_route(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/intern/dashboard');

        $response->assertRedirect();
    }

    public function test_supervisor_cannot_access_intern_route(): void
    {
        $supervisor = User::factory()->supervisor()->create();

        $response = $this->actingAs($supervisor)->get('/intern/dashboard');

        $response->assertRedirect();
    }

    // ─── EnsureProfileComplete Middleware ────────────────

    public function test_intern_with_complete_profile_can_access_intern_route(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->complete()->create([
            'user_id' => $intern->id,
            'full_name' => 'Test User',
            'institution_name' => 'Test University',
            'major' => 'Computer Science',
            'student_id' => 'STU-12345',
            'cv_url' => 'interns/cv/test.pdf',
        ]);

        $response = $this->actingAs($intern)->get('/intern/vacancies');

        $response->assertStatus(200);
    }

    public function test_intern_without_complete_profile_cannot_apply(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->minimal()->create([
            'user_id' => $intern->id,
        ]);

        $response = $this->actingAs($intern)->postJson('/api/v1/applications', [
            'vacancy_id' => 'non-existent',
        ]);

        $response->assertStatus(422);
    }
}
