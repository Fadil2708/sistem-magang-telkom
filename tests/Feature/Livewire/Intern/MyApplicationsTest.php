<?php

namespace Tests\Feature\Livewire\Intern;

use App\Livewire\Intern\MyApplications;
use App\Models\Application;
use App\Models\Vacancy;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class MyApplicationsTest extends TestCase
{
    public function test_confirm_cancel_sets_id(): void
    {
        $intern = User::factory()->intern()->create();
        $application = Application::factory()->submitted()->create(['intern_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(MyApplications::class)
            ->call('confirmCancel', $application->id)
            ->assertSet('confirmingCancelId', $application->id);
    }

    public function test_cancel_submitted_application(): void
    {
        $intern = User::factory()->intern()->create();
        $vacancy = Vacancy::factory()->open()->create();
        $application = Application::factory()->submitted()->create([
            'intern_id' => $intern->id,
            'vacancy_id' => $vacancy->id,
        ]);

        Livewire::actingAs($intern)
            ->test(MyApplications::class)
            ->call('confirmCancel', $application->id)
            ->call('cancel')
            ->assertDispatched('toast', message: 'Lamaran berhasil dibatalkan.', type: 'success')
            ->assertSet('confirmingCancelId', null);

        $this->assertEquals('cancelled', $application->fresh()->status);
    }

    public function test_cancel_non_submitted_application_fails(): void
    {
        $intern = User::factory()->intern()->create();
        $application = Application::factory()->underReview()->create(['intern_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(MyApplications::class)
            ->call('confirmCancel', $application->id)
            ->call('cancel')
            ->assertDispatched('toast', message: 'Hanya lamaran dengan status submitted yang dapat dibatalkan.', type: 'error')
            ->assertSet('confirmingCancelId', null);

        $this->assertEquals('under_review', $application->fresh()->status);
    }

    public function test_render_shows_applications(): void
    {
        $intern = User::factory()->intern()->create();
        Application::factory(2)->submitted()->create(['intern_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(MyApplications::class)
            ->assertSeeHtml('submitted');
    }
}
