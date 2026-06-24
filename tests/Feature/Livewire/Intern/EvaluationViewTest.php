<?php

namespace Tests\Feature\Livewire\Intern;

use App\Livewire\Intern\EvaluationView;
use App\Models\Evaluation;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class EvaluationViewTest extends TestCase
{
    public function test_mount_without_internship(): void
    {
        $intern = User::factory()->intern()->create();

        Livewire::actingAs($intern)
            ->test(EvaluationView::class)
            ->assertSet('internship', null);
    }

    public function test_mount_with_completed_internship(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $supervisor = User::factory()->supervisor()->create();
        $internship = Internship::factory()->completed()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $evaluation = Evaluation::factory()->create([
            'internship_id' => $internship->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $evaluation->calculateFinalScore();
        $evaluation->save();

        Livewire::actingAs($intern)
            ->test(EvaluationView::class)
            ->assertSet('internship.id', $internship->id);
    }

    public function test_mount_with_terminated_internship(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->terminated()->create(['intern_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(EvaluationView::class)
            ->assertSet('internship.id', $internship->id);
    }
}
