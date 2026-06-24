<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\UserForm;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class UserFormTest extends TestCase
{
    public function test_can_create_intern_user(): void
    {
        $admin = User::factory()->admin()->create();

        Livewire::actingAs($admin)
            ->test(UserForm::class)
            ->set('email', 'newintern@test.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->set('role', 'intern')
            ->call('save');

        $this->assertDatabaseHas('users', ['email' => 'newintern@test.com', 'role' => 'intern']);
        $this->assertDatabaseHas('intern_profiles', ['user_id' => User::where('email', 'newintern@test.com')->first()->id]);
    }

    public function test_can_create_supervisor_user(): void
    {
        $admin = User::factory()->admin()->create();

        Livewire::actingAs($admin)
            ->test(UserForm::class)
            ->set('email', 'newsup@test.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->set('role', 'supervisor')
            ->call('save');

        $this->assertDatabaseHas('users', ['email' => 'newsup@test.com', 'role' => 'supervisor']);
        $this->assertDatabaseHas('supervisor_profiles', ['user_id' => User::where('email', 'newsup@test.com')->first()->id]);
    }

    public function test_can_edit_existing_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['email' => 'old@test.com']);

        Livewire::actingAs($admin)
            ->test(UserForm::class, ['id' => $user->id])
            ->assertSet('email', 'old@test.com')
            ->set('email', 'updated@test.com')
            ->call('save');

        $this->assertDatabaseHas('users', ['email' => 'updated@test.com']);
    }

    public function test_validation_fails_without_email(): void
    {
        $admin = User::factory()->admin()->create();

        Livewire::actingAs($admin)
            ->test(UserForm::class)
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('save')
            ->assertHasErrors(['email']);
    }

    public function test_password_must_confirmed(): void
    {
        $admin = User::factory()->admin()->create();

        Livewire::actingAs($admin)
            ->test(UserForm::class)
            ->set('email', 'test@test.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'different')
            ->call('save')
            ->assertHasErrors(['password']);
    }

    public function test_cannot_duplicate_email_on_create(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->create(['email' => 'exists@test.com']);

        Livewire::actingAs($admin)
            ->test(UserForm::class)
            ->set('email', 'exists@test.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('save')
            ->assertHasErrors(['email']);
    }
}
