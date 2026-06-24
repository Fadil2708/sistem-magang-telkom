<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\VacancyForm;
use App\Models\Vacancy;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class VacancyFormTest extends TestCase
{
    public function test_can_create_vacancy(): void
    {
        $admin = User::factory()->admin()->create();
        $tomorrow = now()->addDay()->format('Y-m-d');
        $endDate = now()->addMonths(3)->format('Y-m-d');
        $deadline = now()->format('Y-m-d');

        Livewire::actingAs($admin)
            ->test(VacancyForm::class)
            ->set('title', 'Software Engineer Intern')
            ->set('division', 'IT')
            ->set('description', 'Join our team as an intern.')
            ->set('qualifications', 'Must be a student.')
            ->set('quota', 2)
            ->set('start_date', $tomorrow)
            ->set('end_date', $endDate)
            ->set('application_deadline', $deadline)
            ->call('save')
            ->assertDispatched('vacancy-saved');

        $this->assertDatabaseHas('vacancies', ['title' => 'Software Engineer Intern']);
    }

    public function test_can_edit_vacancy(): void
    {
        $admin = User::factory()->admin()->create();
        $vacancy = Vacancy::factory()->create(['title' => 'Original Title']);
        $tomorrow = now()->addDay()->format('Y-m-d');
        $endDate = now()->addMonths(3)->format('Y-m-d');
        $deadline = now()->format('Y-m-d');

        Livewire::actingAs($admin)
            ->test(VacancyForm::class, ['id' => $vacancy->id])
            ->assertSet('title', 'Original Title')
            ->set('title', 'Updated Title')
            ->set('description', 'Updated description.')
            ->set('qualifications', 'Updated qualifications.')
            ->set('start_date', $tomorrow)
            ->set('end_date', $endDate)
            ->set('application_deadline', $deadline)
            ->call('save')
            ->assertDispatched('vacancy-saved');

        $this->assertEquals('Updated Title', $vacancy->fresh()->title);
    }

    public function test_validation_fails_without_title(): void
    {
        $admin = User::factory()->admin()->create();

        Livewire::actingAs($admin)
            ->test(VacancyForm::class)
            ->set('description', 'Desc')
            ->set('qualifications', 'Quals')
            ->call('save')
            ->assertHasErrors(['title']);
    }

    public function test_quota_must_be_at_least_one(): void
    {
        $admin = User::factory()->admin()->create();

        Livewire::actingAs($admin)
            ->test(VacancyForm::class)
            ->set('quota', 0)
            ->call('save')
            ->assertHasErrors(['quota']);
    }
}
