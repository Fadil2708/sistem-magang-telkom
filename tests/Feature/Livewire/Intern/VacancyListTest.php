<?php

namespace Tests\Feature\Livewire\Intern;

use App\Livewire\Intern\VacancyList;
use App\Models\Vacancy;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class VacancyListTest extends TestCase
{
    public function test_can_search_vacancies(): void
    {
        $intern = User::factory()->intern()->create();
        Vacancy::factory()->open()->create(['title' => 'Software Engineer']);
        Vacancy::factory()->open()->create(['title' => 'Data Analyst']);

        Livewire::actingAs($intern)
            ->test(VacancyList::class)
            ->set('search', 'Software')
            ->assertSee('Software Engineer')
            ->assertDontSee('Data Analyst');
    }

    public function test_can_filter_by_division(): void
    {
        $intern = User::factory()->intern()->create();
        Vacancy::factory()->open()->create(['title' => 'IT Position', 'division' => 'IT']);
        Vacancy::factory()->open()->create(['title' => 'HR Position', 'division' => 'HR']);

        Livewire::actingAs($intern)
            ->test(VacancyList::class)
            ->set('filterDivision', 'IT')
            ->assertSee('IT Position')
            ->assertDontSee('HR Position');
    }

    public function test_only_shows_open_vacancies(): void
    {
        $intern = User::factory()->intern()->create();
        Vacancy::factory()->open()->create(['title' => 'Open Position']);
        Vacancy::factory()->closed()->create(['title' => 'Closed Position']);

        Livewire::actingAs($intern)
            ->test(VacancyList::class)
            ->assertSee('Open Position')
            ->assertDontSee('Closed Position');
    }

    public function test_hides_expired_vacancies(): void
    {
        $intern = User::factory()->intern()->create();
        Vacancy::factory()->open()->create(['title' => 'Active Position', 'application_deadline' => now()->addDays(5)]);
        Vacancy::factory()->open()->create(['title' => 'Expired Position', 'application_deadline' => now()->subDay()]);

        Livewire::actingAs($intern)
            ->test(VacancyList::class)
            ->assertSee('Active Position')
            ->assertDontSee('Expired Position');
    }
}
