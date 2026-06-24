<?php

namespace Tests\Feature\Livewire\Intern;

use App\Livewire\Intern\DashboardStats;
use App\Models\Application;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\Logbook;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardStatsTest extends TestCase
{
    public function test_mount_without_internship(): void
    {
        $intern = User::factory()->intern()->create();

        Livewire::actingAs($intern)
            ->test(DashboardStats::class)
            ->assertSet('applicationStatus', '-')
            ->assertSet('internshipStatus', '-');
    }

    public function test_mount_with_internship(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);
        Logbook::factory()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
            'activity_date' => today(),
        ]);

        Livewire::actingAs($intern)
            ->test(DashboardStats::class)
            ->assertSet('internshipStatus', 'active')
            ->assertSet('logbookToday', true)
            ->assertSet('logbookThisMonth', 1);
    }
}
