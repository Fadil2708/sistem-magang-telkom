<?php

namespace Tests\Feature\Http;

use App\Models\User;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    public function test_admin_can_list_users(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->intern()->count(3)->create();

        $response = $this->actingAs($admin)->getJson('/api/v1/users');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_non_admin_cannot_list_users(): void
    {
        $intern = User::factory()->intern()->create();

        $response = $this->actingAs($intern)->getJson('/api/v1/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_intern_user(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->postJson('/api/v1/users', [
            'email' => 'intern@test.com',
            'password' => 'password123',
            'role' => 'intern',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true);
        $this->assertDatabaseHas('users', ['email' => 'intern@test.com']);
    }

    public function test_admin_can_create_supervisor_user(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->postJson('/api/v1/users', [
            'email' => 'supervisor@test.com',
            'password' => 'password123',
            'role' => 'supervisor',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('supervisor_profiles', ['user_id' => User::where('email', 'supervisor@test.com')->first()->id]);
    }

    public function test_admin_cannot_create_user_without_email(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->postJson('/api/v1/users', [
            'password' => 'password123',
            'role' => 'intern',
        ]);

        $response->assertStatus(422);
    }

    public function test_admin_cannot_duplicate_email(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->create(['email' => 'exists@test.com']);

        $response = $this->actingAs($admin)->postJson('/api/v1/users', [
            'email' => 'exists@test.com',
            'password' => 'password123',
            'role' => 'intern',
        ]);

        $response->assertStatus(422);
    }

    public function test_admin_can_show_user(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();

        $response = $this->actingAs($admin)->getJson('/api/v1/users/' . $intern->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $intern->id);
    }

    public function test_admin_can_update_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['email' => 'old@test.com']);

        $response = $this->actingAs($admin)->putJson('/api/v1/users/' . $user->id, [
            'email' => 'updated@test.com',
            'is_active' => false,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'updated@test.com', 'is_active' => false]);
    }

    public function test_admin_can_deactivate_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->actingAs($admin)->deleteJson('/api/v1/users/' . $user->id);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'is_active' => false]);
    }
}
