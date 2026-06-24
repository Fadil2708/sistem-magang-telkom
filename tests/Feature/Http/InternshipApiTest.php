<?php

namespace Tests\Feature\Http;

use App\Models\Internship;
use App\Models\User;
use Tests\TestCase;

class InternshipApiTest extends TestCase
{
    public function test_admin_can_list_internships(): void
    {
        $admin = User::factory()->admin()->create();
        Internship::factory()->active()->count(3)->create();

        $response = $this->actingAs($admin)->getJson('/api/v1/internships');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_admin_can_filter_internships_by_status(): void
    {
        $admin = User::factory()->admin()->create();
        Internship::factory()->active()->create();
        Internship::factory()->completed()->create();

        $response = $this->actingAs($admin)->getJson('/api/v1/internships?status=completed');

        $response->assertStatus(200);
    }

    public function test_intern_can_view_my_internship(): void
    {
        $intern = User::factory()->intern()->create();
        Internship::factory()->active()->create(['intern_id' => $intern->id]);

        $response = $this->actingAs($intern)->getJson('/api/v1/internships/my');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_supervisor_cannot_view_intern_my_internship(): void
    {
        $supervisor = User::factory()->supervisor()->create();

        $response = $this->actingAs($supervisor)->getJson('/api/v1/internships/my');

        $response->assertStatus(403);
    }

    public function test_supervisor_can_view_supervised_internships(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        Internship::factory()->active()->create(['supervisor_id' => $supervisor->id]);

        $response = $this->actingAs($supervisor)->getJson('/api/v1/internships/supervised');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_intern_cannot_view_supervised(): void
    {
        $intern = User::factory()->intern()->create();

        $response = $this->actingAs($intern)->getJson('/api/v1/internships/supervised');

        $response->assertStatus(403);
    }

    public function test_user_can_view_internship_detail(): void
    {
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);

        $response = $this->actingAs($intern)->getJson('/api/v1/internships/' . $internship->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $internship->id);
    }

    public function test_admin_can_assign_supervisor(): void
    {
        $admin = User::factory()->admin()->create();
        $supervisor = User::factory()->supervisor()->create();
        $internship = Internship::factory()->active()->create();

        $response = $this->actingAs($admin)->patchJson('/api/v1/internships/' . $internship->id . '/supervisor', [
            'supervisor_id' => $supervisor->id,
        ]);

        $response->assertStatus(200);
        $this->assertEquals($supervisor->id, $internship->fresh()->supervisor_id);
    }

    public function test_admin_cannot_assign_non_supervisor(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create();

        $response = $this->actingAs($admin)->patchJson('/api/v1/internships/' . $internship->id . '/supervisor', [
            'supervisor_id' => $intern->id,
        ]);

        $response->assertStatus(422);
    }

    public function test_admin_can_update_internship_status(): void
    {
        $admin = User::factory()->admin()->create();
        $internship = Internship::factory()->active()->create();

        $response = $this->actingAs($admin)->patchJson('/api/v1/internships/' . $internship->id . '/status', [
            'status' => 'completed',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('completed', $internship->fresh()->status);
    }

    public function test_admin_cannot_update_non_active_internship(): void
    {
        $admin = User::factory()->admin()->create();
        $internship = Internship::factory()->completed()->create();

        $response = $this->actingAs($admin)->patchJson('/api/v1/internships/' . $internship->id . '/status', [
            'status' => 'terminated',
        ]);

        $response->assertStatus(422);
    }

    public function test_intern_cannot_update_internship_supervisor(): void
    {
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);
        $supervisor = User::factory()->supervisor()->create();

        $response = $this->actingAs($intern)->patchJson('/api/v1/internships/' . $internship->id . '/supervisor', [
            'supervisor_id' => $supervisor->id,
        ]);

        $response->assertStatus(403);
    }
}
