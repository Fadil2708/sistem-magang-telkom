<?php

namespace Tests\Feature\Http;

use App\Models\InternProfile;
use App\Models\Vacancy;
use App\Models\User;
use Tests\TestCase;

class ApplicationFlowTest extends TestCase
{
    public function test_intern_can_apply(): void
    {
        $vacancy = Vacancy::factory()->open()->create(['quota' => 5]);
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->complete()->create([
            'user_id' => $intern->id,
            'full_name' => 'Test',
            'institution_name' => 'Univ',
            'major' => 'CS',
            'student_id' => 'STU-001',
            'cv_url' => 'interns/cv/test.pdf',
        ]);

        $response = $this->actingAs($intern)->postJson('/api/v1/applications', [
            'vacancy_id' => $vacancy->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.status', 'submitted');
    }

    public function test_intern_cannot_apply_without_complete_profile(): void
    {
        $vacancy = Vacancy::factory()->open()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->minimal()->create(['user_id' => $intern->id]);

        $response = $this->actingAs($intern)->postJson('/api/v1/applications', [
            'vacancy_id' => $vacancy->id,
        ]);

        $response->assertStatus(422);
    }

    public function test_non_intern_cannot_apply(): void
    {
        $admin = User::factory()->admin()->create();
        $vacancy = Vacancy::factory()->open()->create();

        $response = $this->actingAs($admin)->postJson('/api/v1/applications', [
            'vacancy_id' => $vacancy->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_intern_can_cancel_own_application(): void
    {
        $intern = User::factory()->intern()->create();
        $vacancy = Vacancy::factory()->open()->create();
        $application = \App\Models\Application::factory()->submitted()->create([
            'intern_id' => $intern->id,
            'vacancy_id' => $vacancy->id,
        ]);

        $response = $this->actingAs($intern)->patchJson('/api/v1/applications/' . $application->id . '/cancel');

        $response->assertStatus(200);
        $this->assertEquals('cancelled', $application->fresh()->status);
    }

    public function test_intern_cannot_cancel_non_submitted_application(): void
    {
        $intern = User::factory()->intern()->create();
        $application = \App\Models\Application::factory()->underReview()->create(['intern_id' => $intern->id]);

        $response = $this->actingAs($intern)->patchJson('/api/v1/applications/' . $application->id . '/cancel');

        $response->assertStatus(422);
    }

    public function test_admin_can_update_status(): void
    {
        $admin = User::factory()->admin()->create();
        $application = \App\Models\Application::factory()->submitted()->create();

        $response = $this->actingAs($admin)->patchJson('/api/v1/applications/' . $application->id . '/status', [
            'status' => 'under_review',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('under_review', $application->fresh()->status);
    }

    public function test_admin_can_accept_application(): void
    {
        $admin = User::factory()->admin()->create();
        $application = \App\Models\Application::factory()->interviewScheduled()->create();

        $response = $this->actingAs($admin)->patchJson('/api/v1/applications/' . $application->id . '/status', [
            'status' => 'accepted',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('accepted', $application->fresh()->status);
    }

    public function test_admin_can_reject_application(): void
    {
        $admin = User::factory()->admin()->create();
        $application = \App\Models\Application::factory()->underReview()->create();

        $response = $this->actingAs($admin)->patchJson('/api/v1/applications/' . $application->id . '/status', [
            'status' => 'rejected',
            'rejection_reason' => 'Not qualified',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('rejected', $application->fresh()->status);
        $this->assertEquals('Not qualified', $application->fresh()->rejection_reason);
    }
}
