<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\SupervisorMapping;
use App\Models\Internship;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class SupervisorMappingTest extends TestCase
{
    public function test_can_assign_supervisor(): void
    {
        $admin = User::factory()->admin()->create();
        $supervisor = User::factory()->supervisor()->create();
        $internship = Internship::factory()->active()->create();

        Livewire::actingAs($admin)
            ->test(SupervisorMapping::class)
            ->call('assignSupervisor', $internship->id, $supervisor->id);

        $this->assertEquals($supervisor->id, $internship->fresh()->supervisor_id);
    }

    public function test_cannot_assign_non_supervisor(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create();

        $originalSupervisorId = $internship->supervisor_id;

        Livewire::actingAs($admin)
            ->test(SupervisorMapping::class)
            ->call('assignSupervisor', $internship->id, $intern->id);

        $this->assertEquals($originalSupervisorId, $internship->fresh()->supervisor_id);
    }

    public function test_cannot_assign_inactive_supervisor(): void
    {
        $admin = User::factory()->admin()->create();
        $supervisor = User::factory()->supervisor()->create(['is_active' => false]);
        $internship = Internship::factory()->active()->create();

        $originalSupervisorId = $internship->supervisor_id;

        Livewire::actingAs($admin)
            ->test(SupervisorMapping::class)
            ->call('assignSupervisor', $internship->id, $supervisor->id);

        $this->assertEquals($originalSupervisorId, $internship->fresh()->supervisor_id);
    }

    public function test_can_filter_by_status(): void
    {
        $admin = User::factory()->admin()->create();

        Livewire::actingAs($admin)
            ->test(SupervisorMapping::class)
            ->set('filterStatus', 'completed')
            ->assertSet('filterStatus', 'completed');
    }
}
