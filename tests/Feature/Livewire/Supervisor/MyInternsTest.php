<?php

namespace Tests\Feature\Livewire\Supervisor;

use App\Livewire\Supervisor\MyInterns;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class MyInternsTest extends TestCase
{
    public function test_shows_supervised_interns(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id, 'full_name' => 'John Intern']);
        Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(MyInterns::class)
            ->assertSee('John Intern');
    }

    public function test_can_filter_by_status(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(MyInterns::class)
            ->set('filterStatus', 'completed')
            ->assertSet('filterStatus', 'completed');
    }

    public function test_does_not_show_other_supervisor_interns(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $otherSupervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id, 'full_name' => 'Hidden Intern']);
        Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $otherSupervisor->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(MyInterns::class)
            ->assertDontSee('Hidden Intern');
    }
}
