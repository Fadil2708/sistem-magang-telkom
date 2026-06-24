<?php

namespace Tests\Feature\Livewire\Intern;

use App\Livewire\Intern\ApplicationForm;
use App\Models\InternProfile;
use App\Models\User;
use App\Models\Vacancy;
use Livewire\Livewire;
use Tests\TestCase;

class ApplicationFormTest extends TestCase
{
    public function test_mount_checks_profile_completeness(): void
    {
        $intern = User::factory()->intern()->create();
        $vacancy = Vacancy::factory()->open()->create();

        Livewire::actingAs($intern)
            ->test(ApplicationForm::class, ['vacancyId' => $vacancy->id])
            ->assertSet('profileComplete', false);
    }

    public function test_intern_with_complete_profile_can_apply(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->complete()->create(['user_id' => $intern->id]);
        $vacancy = Vacancy::factory()->open()->create();

        Livewire::actingAs($intern)
            ->test(ApplicationForm::class, ['vacancyId' => $vacancy->id])
            ->assertSet('profileComplete', true)
            ->call('apply')
            ->assertSet('hasApplied', true);

        $this->assertDatabaseHas('applications', [
            'intern_id' => $intern->id,
            'vacancy_id' => $vacancy->id,
        ]);
    }

    public function test_intern_with_incomplete_profile_cannot_apply(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->minimal()->create(['user_id' => $intern->id]);
        $vacancy = Vacancy::factory()->open()->create();

        Livewire::actingAs($intern)
            ->test(ApplicationForm::class, ['vacancyId' => $vacancy->id])
            ->assertSet('profileComplete', false);
    }

    public function test_already_applied_shows_status(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->complete()->create(['user_id' => $intern->id]);
        $vacancy = Vacancy::factory()->open()->create();
        $intern->applications()->create([
            'vacancy_id' => $vacancy->id,
            'status' => 'submitted',
            'applied_at' => now(),
        ]);

        Livewire::actingAs($intern)
            ->test(ApplicationForm::class, ['vacancyId' => $vacancy->id])
            ->assertSet('hasApplied', true);
    }
}
