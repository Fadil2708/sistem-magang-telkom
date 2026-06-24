<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\UserList;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class UserListTest extends TestCase
{
    public function test_can_search_users_by_email(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->create(['email' => 'unique@test.com']);

        Livewire::actingAs($admin)
            ->test(UserList::class)
            ->set('search', 'unique')
            ->assertSee('unique@test.com');
    }

    public function test_can_filter_by_role(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->supervisor()->create();
        User::factory()->intern()->create();

        Livewire::actingAs($admin)
            ->test(UserList::class)
            ->set('filterRole', 'supervisor')
            ->assertSee('supervisor');
    }

    public function test_confirm_deactivate_sets_id(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        Livewire::actingAs($admin)
            ->test(UserList::class)
            ->call('confirmDeactivate', $user->id)
            ->assertSet('confirmingDeactivateId', $user->id);
    }

    public function test_can_deactivate_other_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['is_active' => true]);

        Livewire::actingAs($admin)
            ->test(UserList::class)
            ->call('confirmDeactivate', $user->id)
            ->call('deactivate');

        $this->assertFalse($user->fresh()->is_active);
    }

    public function test_can_reactivate_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['is_active' => false]);

        Livewire::actingAs($admin)
            ->test(UserList::class)
            ->call('confirmDeactivate', $user->id)
            ->call('deactivate');

        $this->assertTrue($user->fresh()->is_active);
    }

    public function test_cannot_deactivate_self(): void
    {
        $admin = User::factory()->admin()->create();

        Livewire::actingAs($admin)
            ->test(UserList::class)
            ->call('confirmDeactivate', $admin->id)
            ->call('deactivate')
            ->assertDispatched('toast', message: 'Tidak bisa menonaktifkan akun sendiri.', type: 'error');

        $this->assertTrue($admin->fresh()->is_active);
    }
}
