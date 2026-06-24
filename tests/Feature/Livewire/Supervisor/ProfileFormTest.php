<?php

namespace Tests\Feature\Livewire\Supervisor;

use App\Livewire\Supervisor\ProfileForm;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileFormTest extends TestCase
{
    public function test_mount_existing_profile(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $supervisor->supervisorProfile()->create([
            'full_name' => 'Budi Hartono',
            'employee_id' => 'EMP001',
            'division' => 'IT',
            'position' => 'Lead',
            'phone' => '08123456789',
        ]);

        Livewire::actingAs($supervisor)
            ->test(ProfileForm::class)
            ->assertSet('full_name', 'Budi Hartono')
            ->assertSet('employee_id', 'EMP001')
            ->assertSet('division', 'IT');
    }

    public function test_mount_without_profile(): void
    {
        $supervisor = User::factory()->supervisor()->create();

        Livewire::actingAs($supervisor)
            ->test(ProfileForm::class)
            ->assertSet('full_name', '')
            ->assertSet('employee_id', '');
    }

    public function test_can_update_profile(): void
    {
        $supervisor = User::factory()->supervisor()->create();

        Livewire::actingAs($supervisor)
            ->test(ProfileForm::class)
            ->set('full_name', 'Siti Nurhaliza')
            ->set('division', 'HRD')
            ->set('position', 'Manager')
            ->call('save');

        $this->assertDatabaseHas('supervisor_profiles', [
            'user_id' => $supervisor->id,
            'full_name' => 'Siti Nurhaliza',
            'division' => 'HRD',
        ]);
    }

    public function test_validation_fails_without_name(): void
    {
        $supervisor = User::factory()->supervisor()->create();

        Livewire::actingAs($supervisor)
            ->test(ProfileForm::class)
            ->set('full_name', '')
            ->call('save')
            ->assertHasErrors(['full_name']);
    }
}
