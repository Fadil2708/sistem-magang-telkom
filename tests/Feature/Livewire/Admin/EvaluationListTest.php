<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\EvaluationList;
use App\Models\Evaluation;
use App\Models\Internship;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class EvaluationListTest extends TestCase
{
    public function test_can_filter_by_grade(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        $supervisor = User::factory()->supervisor()->create();
        $internship = Internship::factory()->completed()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $evaluation = Evaluation::factory()->withGrade('A')->create([
            'internship_id' => $internship->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $evaluation->calculateFinalScore();
        $evaluation->save();

        Livewire::actingAs($admin)
            ->test(EvaluationList::class)
            ->set('filterGrade', 'A')
            ->assertSet('filterGrade', 'A');
    }

    public function test_renders_evaluation_list(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        $supervisor = User::factory()->supervisor()->create();
        $internship = Internship::factory()->completed()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $evaluation = Evaluation::factory()->withGrade('B')->create([
            'internship_id' => $internship->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $evaluation->calculateFinalScore();
        $evaluation->save();

        Livewire::actingAs($admin)
            ->test(EvaluationList::class)
            ->assertSee('B');
    }
}
