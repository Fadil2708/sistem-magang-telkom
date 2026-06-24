<?php

namespace Tests\Feature\Http;

use App\Models\Vacancy;
use App\Models\User;
use Tests\TestCase;

class VacancyApiTest extends TestCase
{
    public function test_index_returns_vacancies(): void
    {
        Vacancy::factory(3)->open()->create();
        $user = User::factory()->intern()->create();

        $response = $this->actingAs($user)->getJson('/api/v1/vacancies');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_show_returns_vacancy(): void
    {
        $vacancy = Vacancy::factory()->open()->create();
        $user = User::factory()->intern()->create();

        $response = $this->actingAs($user)->getJson('/api/v1/vacancies/' . $vacancy->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $vacancy->id);
    }

    public function test_admin_can_create_vacancy(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->postJson('/api/v1/vacancies', [
            'title' => 'Software Engineer Intern',
            'description' => 'Join our team and work on exciting projects',
            'qualifications' => 'Good communication and technical skills',
            'quota' => 5,
            'start_date' => now()->addMonth()->format('Y-m-d'),
            'end_date' => now()->addMonths(3)->format('Y-m-d'),
            'application_deadline' => now()->addWeeks(2)->format('Y-m-d'),
            'status' => 'open',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'Software Engineer Intern');
    }

    public function test_non_admin_cannot_create_vacancy(): void
    {
        $intern = User::factory()->intern()->create();

        $response = $this->actingAs($intern)->postJson('/api/v1/vacancies', [
            'title' => 'Software Engineer Intern',
            'description' => 'Join our team',
            'qualifications' => 'Good communication',
            'quota' => 5,
            'start_date' => now()->addMonth()->format('Y-m-d'),
            'end_date' => now()->addMonths(3)->format('Y-m-d'),
            'application_deadline' => now()->addWeeks(2)->format('Y-m-d'),
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_vacancy(): void
    {
        $admin = User::factory()->admin()->create();
        $vacancy = Vacancy::factory()->create(['created_by' => $admin->id]);

        $response = $this->actingAs($admin)->putJson('/api/v1/vacancies/' . $vacancy->id, [
            'title' => 'Updated Title',
            'description' => 'Updated description for the role',
            'qualifications' => 'Updated qualifications required',
            'quota' => 3,
            'start_date' => now()->addMonth()->format('Y-m-d'),
            'end_date' => now()->addMonths(3)->format('Y-m-d'),
            'application_deadline' => now()->addWeeks(2)->format('Y-m-d'),
            'status' => $vacancy->status,
        ]);

        $response->assertStatus(200);
        $this->assertEquals('Updated Title', $vacancy->fresh()->title);
    }

    public function test_admin_can_toggle_status(): void
    {
        $admin = User::factory()->admin()->create();
        $vacancy = Vacancy::factory()->open()->create(['created_by' => $admin->id]);

        $response = $this->actingAs($admin)->patchJson('/api/v1/vacancies/' . $vacancy->id . '/status', [
            'status' => 'closed',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('closed', $vacancy->fresh()->status);
    }

    public function test_admin_can_delete_vacancy(): void
    {
        $admin = User::factory()->admin()->create();
        $vacancy = Vacancy::factory()->create(['created_by' => $admin->id]);

        $response = $this->actingAs($admin)->deleteJson('/api/v1/vacancies/' . $vacancy->id);

        $response->assertStatus(200);
        $this->assertNull(Vacancy::find($vacancy->id));
    }
}
