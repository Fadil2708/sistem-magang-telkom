<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\DashboardStats;
use App\Models\Application;
use App\Models\Internship;
use App\Models\Logbook;
use App\Models\User;
use App\Models\Vacancy;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardStatsTest extends TestCase
{
    public function test_mount_with_data(): void
    {
        $admin = User::factory()->admin()->create();
        $supervisor = User::factory()->supervisor()->create();

        $vacancy = Vacancy::factory()->open()->create(['created_by' => $admin->id]);
        $intern1 = User::factory()->intern()->create();
        $app1 = Application::factory()->create([
            'intern_id' => $intern1->id, 'vacancy_id' => $vacancy->id, 'status' => 'accepted',
        ]);
        $internship = Internship::create([
            'application_id' => $app1->id, 'intern_id' => $intern1->id,
            'supervisor_id' => $supervisor->id, 'vacancy_id' => $vacancy->id,
            'status' => 'active', 'actual_start_date' => now(), 'actual_end_date' => now()->addMonths(3),
        ]);
        Logbook::factory()->submitted()->create([
            'internship_id' => $internship->id, 'intern_id' => $intern1->id,
        ]);

        Livewire::actingAs($admin)
            ->test(DashboardStats::class)
            ->assertSet('totalInternsActive', 1)
            ->assertSet('totalVacanciesOpen', 1)
            ->assertSet('totalLogbooksPending', 1);
    }

    public function test_mount_with_empty_data(): void
    {
        $admin = User::factory()->admin()->create();

        Livewire::actingAs($admin)
            ->test(DashboardStats::class)
            ->assertSet('totalInternsActive', 0);
    }
}
