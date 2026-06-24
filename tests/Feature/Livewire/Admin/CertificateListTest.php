<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\CertificateList;
use App\Models\Certificate;
use App\Models\Evaluation;
use App\Models\Internship;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class CertificateListTest extends TestCase
{
    public function test_can_search_by_certificate_number(): void
    {
        $admin = User::factory()->admin()->create();
        Certificate::factory()->create(['certificate_number' => 'SER-001']);
        Certificate::factory()->create(['certificate_number' => 'SER-002']);

        Livewire::actingAs($admin)
            ->test(CertificateList::class)
            ->set('search', 'SER-001')
            ->assertSet('search', 'SER-001');
    }

    public function test_confirm_issue_sets_id(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        $supervisor = User::factory()->supervisor()->create();
        $internship = Internship::factory()->completed()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        Evaluation::factory()->create([
            'internship_id' => $internship->id,
            'supervisor_id' => $supervisor->id,
        ]);

        Livewire::actingAs($admin)
            ->test(CertificateList::class)
            ->call('confirmIssue', $internship->id)
            ->assertSet('confirmingIssueId', $internship->id);
    }

    public function test_can_issue_certificate(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        $supervisor = User::factory()->supervisor()->create();
        $internship = Internship::factory()->completed()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $evaluation = Evaluation::factory()->create([
            'internship_id' => $internship->id,
            'supervisor_id' => $supervisor->id,
            'evaluated_at' => now(),
        ]);
        $evaluation->calculateFinalScore();
        $evaluation->save();

        Livewire::actingAs($admin)
            ->test(CertificateList::class)
            ->call('confirmIssue', $internship->id)
            ->call('issue');

        $this->assertDatabaseHas('certificates', ['internship_id' => $internship->id]);
    }

    public function test_issue_fails_without_evaluation(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->completed()->create(['intern_id' => $intern->id]);

        Livewire::actingAs($admin)
            ->test(CertificateList::class)
            ->call('confirmIssue', $internship->id)
            ->call('issue')
            ->assertDispatched('toast', message: 'Penilaian belum diisi oleh pembimbing.', type: 'error');
    }
}
