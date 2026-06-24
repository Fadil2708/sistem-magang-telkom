<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\VacancyList;
use App\Models\Application;
use App\Models\User;
use App\Models\Vacancy;
use Livewire\Livewire;
use Tests\TestCase;

class VacancyListTest extends TestCase
{
    public function test_can_search_vacancies(): void
    {
        $admin = User::factory()->admin()->create();
        Vacancy::factory()->create(['title' => 'Software Engineer']);
        Vacancy::factory()->create(['title' => 'Data Analyst']);

        Livewire::actingAs($admin)
            ->test(VacancyList::class)
            ->set('search', 'Software')
            ->assertSee('Software Engineer')
            ->assertDontSee('Data Analyst');
    }

    public function test_can_filter_by_status(): void
    {
        $admin = User::factory()->admin()->create();
        $open = Vacancy::factory()->open()->create(['title' => 'Open Position']);
        $closed = Vacancy::factory()->closed()->create(['title' => 'Closed Position']);

        Livewire::actingAs($admin)
            ->test(VacancyList::class)
            ->set('filterStatus', 'open')
            ->assertSee('Open Position')
            ->assertDontSee('Closed Position');
    }

    public function test_can_delete_vacancy_without_applications(): void
    {
        $admin = User::factory()->admin()->create();
        $vacancy = Vacancy::factory()->create();

        Livewire::actingAs($admin)
            ->test(VacancyList::class)
            ->call('deleteVacancy', $vacancy->id);

        $this->assertDatabaseMissing('vacancies', ['id' => $vacancy->id]);
    }

    public function test_cannot_delete_vacancy_with_applications(): void
    {
        $admin = User::factory()->admin()->create();
        $vacancy = Vacancy::factory()->create();
        Application::factory()->submitted()->create(['vacancy_id' => $vacancy->id]);

        Livewire::actingAs($admin)
            ->test(VacancyList::class)
            ->call('deleteVacancy', $vacancy->id);

        $this->assertDatabaseHas('vacancies', ['id' => $vacancy->id]);
    }

    public function test_can_sort_by_title(): void
    {
        $admin = User::factory()->admin()->create();

        Livewire::actingAs($admin)
            ->test(VacancyList::class)
            ->call('sortBy', 'title')
            ->assertSet('sortField', 'title')
            ->assertSet('sortDirection', 'asc')
            ->call('sortBy', 'title')
            ->assertSet('sortDirection', 'desc');
    }
}
