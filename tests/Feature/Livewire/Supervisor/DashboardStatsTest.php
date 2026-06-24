<?php

namespace Tests\Feature\Livewire\Supervisor;

use App\Livewire\Supervisor\DashboardStats;
use App\Models\Internship;
use App\Models\Logbook;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardStatsTest extends TestCase
{
    public function test_mount_with_data(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        Logbook::factory()->submitted()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(DashboardStats::class)
            ->assertSet('totalInterns', 1)
            ->assertSet('pendingLogbooks', 1);
    }

    public function test_mount_with_empty_data(): void
    {
        $supervisor = User::factory()->supervisor()->create();

        Livewire::actingAs($supervisor)
            ->test(DashboardStats::class)
            ->assertSet('totalInterns', 0);
    }
}
