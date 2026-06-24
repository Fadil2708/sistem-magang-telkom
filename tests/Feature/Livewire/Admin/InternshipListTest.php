<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\InternshipList;
use App\Models\Evaluation;
use App\Models\Internship;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class InternshipListTest extends TestCase
{
    public function test_can_filter_by_status(): void
    {
        $admin = User::factory()->admin()->create();
        Internship::factory()->active()->create();
        Internship::factory()->completed()->create();

        Livewire::actingAs($admin)
            ->test(InternshipList::class)
            ->set('filterStatus', 'active')
            ->assertSet('filterStatus', 'active');
    }

    public function test_confirm_action_sets_id_and_type(): void
    {
        $admin = User::factory()->admin()->create();
        $internship = Internship::factory()->active()->create();

        Livewire::actingAs($admin)
            ->test(InternshipList::class)
            ->call('confirmAction', $internship->id, 'complete')
            ->assertSet('confirmingAction', $internship->id)
            ->assertSet('actionType', 'complete');
    }

    public function test_can_complete_internship(): void
    {
        $admin = User::factory()->admin()->create();
        $internship = Internship::factory()->active()->create();

        Livewire::actingAs($admin)
            ->test(InternshipList::class)
            ->call('confirmAction', $internship->id, 'complete')
            ->call('executeAction');

        $this->assertEquals('completed', $internship->fresh()->status);
    }

    public function test_can_terminate_internship(): void
    {
        $admin = User::factory()->admin()->create();
        $internship = Internship::factory()->active()->create();

        Livewire::actingAs($admin)
            ->test(InternshipList::class)
            ->call('confirmAction', $internship->id, 'terminate')
            ->call('executeAction');

        $this->assertEquals('terminated', $internship->fresh()->status);
    }

    public function test_cannot_change_non_active_internship(): void
    {
        $admin = User::factory()->admin()->create();
        $internship = Internship::factory()->completed()->create();

        Livewire::actingAs($admin)
            ->test(InternshipList::class)
            ->call('confirmAction', $internship->id, 'complete')
            ->call('executeAction')
            ->assertDispatched('toast', message: 'Hanya magang dengan status aktif yang bisa diubah.', type: 'error');

        $this->assertEquals('completed', $internship->fresh()->status);
    }

    public function test_edit_dates_opens_modal(): void
    {
        $admin = User::factory()->admin()->create();
        $internship = Internship::factory()->active()->create([
            'actual_start_date' => now()->subMonth(),
            'actual_end_date' => now()->addMonths(2),
        ]);

        Livewire::actingAs($admin)
            ->test(InternshipList::class)
            ->call('editDates', $internship->id)
            ->assertSet('showDatesModal', true)
            ->assertSet('editingInternshipId', $internship->id);
    }

    public function test_save_dates_updates_internship(): void
    {
        $admin = User::factory()->admin()->create();
        $internship = Internship::factory()->active()->create();

        $newStart = now()->subMonth()->format('Y-m-d');
        $newEnd = now()->addMonth()->format('Y-m-d');

        Livewire::actingAs($admin)
            ->test(InternshipList::class)
            ->call('editDates', $internship->id)
            ->set('actual_start_date', $newStart)
            ->set('actual_end_date', $newEnd)
            ->call('saveDates');

        $fresh = $internship->fresh();
        $this->assertEquals($newStart, $fresh->actual_start_date->format('Y-m-d'));
        $this->assertEquals($newEnd, $fresh->actual_end_date->format('Y-m-d'));
    }

    public function test_confirm_lock_sets_id(): void
    {
        $admin = User::factory()->admin()->create();
        $internship = Internship::factory()->active()->create();

        Livewire::actingAs($admin)
            ->test(InternshipList::class)
            ->call('confirmLock', $internship->id)
            ->assertSet('confirmingLockId', $internship->id);
    }

    public function test_lock_evaluation(): void
    {
        $admin = User::factory()->admin()->create();
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->completed()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $evaluation = Evaluation::factory()->create([
            'internship_id' => $internship->id,
            'supervisor_id' => $supervisor->id,
        ]);

        Livewire::actingAs($admin)
            ->test(InternshipList::class)
            ->call('confirmLock', $internship->id)
            ->call('lockEvaluation');

        $this->assertNotNull($evaluation->fresh()->evaluated_at);
    }

    public function test_lock_fails_without_evaluation(): void
    {
        $admin = User::factory()->admin()->create();
        $internship = Internship::factory()->active()->create();

        Livewire::actingAs($admin)
            ->test(InternshipList::class)
            ->call('confirmLock', $internship->id)
            ->call('lockEvaluation')
            ->assertDispatched('toast', message: 'Penilaian belum diisi oleh pembimbing.', type: 'error');
    }
}
