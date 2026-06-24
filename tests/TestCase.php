<?php

namespace Tests;

use App\Models\Application;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\SupervisorProfile;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function loginAsAdmin(): User
    {
        $user = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $this->actingAs($user);

        return $user;
    }

    protected function loginAsSupervisor(array $profileData = []): User
    {
        $user = User::factory()->create(['role' => 'supervisor', 'is_active' => true]);
        SupervisorProfile::factory()->create(array_merge(['user_id' => $user->id], $profileData));
        $this->actingAs($user);

        return $user;
    }

    protected function loginAsIntern(array $profileData = []): User
    {
        $user = User::factory()->create(['role' => 'intern', 'is_active' => true]);
        InternProfile::factory()->create(array_merge(['user_id' => $user->id], $profileData));
        $this->actingAs($user);

        return $user;
    }

    protected function createFullInternProfile(User $user): InternProfile
    {
        return InternProfile::factory()->create([
            'user_id' => $user->id,
            'full_name' => 'Test Intern',
            'institution_name' => 'Test University',
            'major' => 'Computer Science',
            'student_id' => 'STU-12345',
            'phone' => '081234567890',
            'cv_url' => 'interns/cv/test.pdf',
        ]);
    }

    protected function createCompletedInternship(User $intern, User $supervisor, Vacancy $vacancy): array
    {
        $application = Application::factory()->create([
            'intern_id' => $intern->id,
            'vacancy_id' => $vacancy->id,
            'status' => 'accepted',
        ]);

        $internship = Internship::factory()->create([
            'application_id' => $application->id,
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
            'vacancy_id' => $vacancy->id,
            'status' => 'active',
        ]);

        return ['application' => $application, 'internship' => $internship];
    }
}
