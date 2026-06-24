<?php

namespace Tests\Feature\Http;

use App\Models\InternProfile;
use App\Models\User;
use Tests\TestCase;

class ProfileApiTest extends TestCase
{
    public function test_intern_can_update_profile(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);

        $response = $this->actingAs($intern)->putJson('/api/v1/profile/intern', [
            'full_name'        => 'John Doe Updated',
            'phone'            => '08123456789',
            'institution_name' => 'Test University',
            'institution_type' => 'university',
            'major'            => 'Computer Science',
            'student_id'       => 'STD12345',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true);
        $this->assertDatabaseHas('intern_profiles', [
            'user_id' => $intern->id,
            'full_name' => 'John Doe Updated',
        ]);
    }

    public function test_supervisor_cannot_update_intern_profile(): void
    {
        $supervisor = User::factory()->supervisor()->create();

        $response = $this->actingAs($supervisor)->putJson('/api/v1/profile/intern', [
            'full_name' => 'Should Fail',
        ]);

        $response->assertStatus(403);
    }

    public function test_supervisor_can_update_profile(): void
    {
        $supervisor = User::factory()->supervisor()->create();

        $response = $this->actingAs($supervisor)->putJson('/api/v1/profile/supervisor', [
            'full_name' => 'Supervisor Name',
            'position' => 'Lead Supervisor',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('supervisor_profiles', [
            'user_id' => $supervisor->id,
            'full_name' => 'Supervisor Name',
        ]);
    }

    public function test_intern_cannot_update_supervisor_profile(): void
    {
        $intern = User::factory()->intern()->create();

        $response = $this->actingAs($intern)->putJson('/api/v1/profile/supervisor', [
            'full_name' => 'Should Fail',
        ]);

        $response->assertStatus(403);
    }
}
