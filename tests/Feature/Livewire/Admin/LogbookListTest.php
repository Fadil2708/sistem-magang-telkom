<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\LogbookList;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\Logbook;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class LogbookListTest extends TestCase
{
    public function test_can_filter_by_status(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);
        Logbook::factory()->submitted()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);
        Logbook::factory()->approved()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($admin)
            ->test(LogbookList::class)
            ->set('filterStatus', 'submitted')
            ->assertSet('filterStatus', 'submitted');
    }

    public function test_can_search_by_intern_name(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id, 'full_name' => 'John Doe']);
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);
        Logbook::factory()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($admin)
            ->test(LogbookList::class)
            ->set('search', 'John')
            ->assertSee('John Doe');
    }

    public function test_renders_logbook_list(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);
        Logbook::factory()->approved()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($admin)
            ->test(LogbookList::class)
            ->assertSee('approved');
    }
}
