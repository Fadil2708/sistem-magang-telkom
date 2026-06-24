<?php

namespace Tests\Feature\Livewire\Supervisor;

use App\Livewire\Supervisor\EvaluationForm;
use App\Models\Certificate;
use App\Models\Evaluation;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class EvaluationFormTest extends TestCase
{
    public function test_mount_loads_internship(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->completed()->create([
            'supervisor_id' => $supervisor->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(EvaluationForm::class, ['internshipId' => $internship->id])
            ->assertSet('internship.id', $internship->id);
    }

    public function test_mount_locks_when_evaluated(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->completed()->create([
            'supervisor_id' => $supervisor->id,
            'intern_id' => $intern->id,
        ]);
        Evaluation::factory()->locked()->create([
            'internship_id' => $internship->id,
            'supervisor_id' => $supervisor->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(EvaluationForm::class, ['internshipId' => $internship->id])
            ->assertSet('isLocked', true);
    }

    public function test_save_creates_evaluation(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->completed()->create([
            'supervisor_id' => $supervisor->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(EvaluationForm::class, ['internshipId' => $internship->id])
            ->set('soft_skill_score', '85')
            ->set('hard_skill_score', '85')
            ->set('attendance_score', '85')
            ->set('attitude_score', '85')
            ->call('save');

        $this->assertDatabaseHas('evaluations', [
            'internship_id' => $internship->id,
            'supervisor_id' => $supervisor->id,
        ]);
    }

    public function test_save_validates_scores(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->completed()->create([
            'supervisor_id' => $supervisor->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(EvaluationForm::class, ['internshipId' => $internship->id])
            ->set('soft_skill_score', '-1')
            ->call('confirmSave')
            ->assertHasErrors(['soft_skill_score']);
    }

    public function test_save_locked_evaluation_refused(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->completed()->create([
            'supervisor_id' => $supervisor->id,
            'intern_id' => $intern->id,
        ]);

        Certificate::factory()->create(['internship_id' => $internship->id]);

        Livewire::actingAs($supervisor)
            ->test(EvaluationForm::class, ['internshipId' => $internship->id])
            ->assertSet('isLocked', true)
            ->set('soft_skill_score', '90')
            ->set('hard_skill_score', '90')
            ->set('attendance_score', '90')
            ->set('attitude_score', '90')
            ->call('confirmSave')
            ->assertSet('confirmingSave', false);

        $this->assertDatabaseMissing('evaluations', [
            'internship_id' => $internship->id,
        ]);
    }

    public function test_render_without_internship_id_shows_list(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        Internship::factory()->completed()->create([
            'supervisor_id' => $supervisor->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($supervisor)
            ->test(EvaluationForm::class)
            ->assertViewIs('livewire.supervisor.evaluation-form');
    }
}
