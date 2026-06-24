<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\ReportList;
use App\Models\FinalReport;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class ReportListTest extends TestCase
{
    public function test_can_filter_by_status(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship1 = Internship::factory()->active()->create(['intern_id' => $intern->id]);
        $internship2 = Internship::factory()->active()->create(['intern_id' => $intern->id]);
        FinalReport::factory()->pending()->create([
            'internship_id' => $internship1->id,
            'intern_id' => $intern->id,
        ]);
        FinalReport::factory()->approved()->create([
            'internship_id' => $internship2->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($admin)
            ->test(ReportList::class)
            ->set('filterStatus', 'approved')
            ->assertSet('filterStatus', 'approved');
    }

    public function test_renders_report_list(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id, 'full_name' => 'John Intern']);
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);
        FinalReport::factory()->pending()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
            'title' => 'Laporan Akhir',
        ]);

        Livewire::actingAs($admin)
            ->test(ReportList::class)
            ->assertSee('Laporan Akhir')
            ->assertSee('John Intern');
    }
}
