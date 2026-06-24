<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\ApplicationReview;
use App\Models\Application;
use App\Models\InternProfile;
use App\Models\User;
use App\Models\Vacancy;
use Livewire\Livewire;
use Tests\TestCase;

class ApplicationReviewTest extends TestCase
{
    public function test_open_review_loads_application(): void
    {
        $admin = User::factory()->admin()->create();
        $application = Application::factory()->submitted()->create();

        Livewire::actingAs($admin)
            ->test(ApplicationReview::class)
            ->call('openReview', $application->id)
            ->assertSet('selectedApplicationId', $application->id)
            ->assertSet('showReviewModal', true);
    }

    public function test_update_status_valid_transition(): void
    {
        $admin = User::factory()->admin()->create();
        $application = Application::factory()->submitted()->create();

        Livewire::actingAs($admin)
            ->test(ApplicationReview::class)
            ->call('openReview', $application->id)
            ->set('reviewStatus', 'under_review')
            ->call('updateStatus')
            ->assertDispatched('toast');

        $this->assertEquals('under_review', $application->fresh()->status);
    }

    public function test_update_status_invalid_transition(): void
    {
        $admin = User::factory()->admin()->create();
        $application = Application::factory()->submitted()->create();

        Livewire::actingAs($admin)
            ->test(ApplicationReview::class)
            ->call('openReview', $application->id)
            ->set('reviewStatus', 'accepted')
            ->call('updateStatus')
            ->assertDispatched('toast', message: 'Transisi status tidak valid.', type: 'error');

        $this->assertEquals('submitted', $application->fresh()->status);
    }

    public function test_update_status_rejected_requires_reason(): void
    {
        $admin = User::factory()->admin()->create();
        $vacancy = Vacancy::factory()->open()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $application = Application::factory()->underReview()->create([
            'intern_id' => $intern->id,
            'vacancy_id' => $vacancy->id,
        ]);

        Livewire::actingAs($admin)
            ->test(ApplicationReview::class)
            ->call('openReview', $application->id)
            ->set('reviewStatus', 'rejected')
            ->set('rejectionReason', '')
            ->call('updateStatus')
            ->assertDispatched('toast', message: 'Alasan penolakan wajib diisi.');
    }

    public function test_update_status_interview_requires_date(): void
    {
        $admin = User::factory()->admin()->create();
        $vacancy = Vacancy::factory()->open()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $application = Application::factory()->underReview()->create([
            'intern_id' => $intern->id,
            'vacancy_id' => $vacancy->id,
        ]);

        Livewire::actingAs($admin)
            ->test(ApplicationReview::class)
            ->call('openReview', $application->id)
            ->set('reviewStatus', 'interview_scheduled')
            ->set('interviewDate', '')
            ->call('updateStatus')
            ->assertDispatched('toast', message: 'Tanggal interview wajib diisi.');
    }

    public function test_render_lists_applications(): void
    {
        $admin = User::factory()->admin()->create();
        Application::factory(3)->submitted()->create();

        Livewire::actingAs($admin)
            ->test(ApplicationReview::class)
            ->assertSeeHtml('submitted');
    }
}
