<?php

namespace Tests\Unit\Services;

use App\Exceptions\IncompleteProfileException;
use App\Exceptions\QuotaFullException;
use App\Models\InternProfile;
use App\Models\User;
use App\Models\Vacancy;
use App\Services\ApplicationService;
use Tests\TestCase;

class ApplicationServiceTest extends TestCase
{
    private ApplicationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ApplicationService();
    }

    public function test_apply_success(): void
    {
        $vacancy = Vacancy::factory()->open()->create(['quota' => 5]);
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->complete()->create([
            'user_id' => $intern->id,
            'full_name' => 'Test User',
            'institution_name' => 'Univ',
            'major' => 'CS',
            'student_id' => 'STU-001',
            'cv_url' => 'interns/cv/test.pdf',
        ]);

        $application = $this->service->apply($intern, $vacancy->id);

        $this->assertEquals('submitted', $application->status);
        $this->assertEquals($intern->id, $application->intern_id);
        $this->assertEquals($vacancy->id, $application->vacancy_id);
        $this->assertNotNull($application->applied_at);
    }

    public function test_apply_throws_when_profile_incomplete_missing_full_name(): void
    {
        $vacancy = Vacancy::factory()->open()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->complete()->create([
            'user_id' => $intern->id,
            'full_name' => '',
            'institution_name' => 'Univ',
            'major' => 'CS',
            'student_id' => 'STU-001',
            'cv_url' => 'interns/cv/test.pdf',
        ]);

        $this->expectException(IncompleteProfileException::class);
        $this->expectExceptionMessage('full_name');

        $this->service->apply($intern, $vacancy->id);
    }

    public function test_apply_throws_when_profile_incomplete_missing_cv_url(): void
    {
        $vacancy = Vacancy::factory()->open()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create([
            'user_id' => $intern->id,
            'full_name' => 'Test',
            'institution_name' => 'Univ',
            'major' => 'CS',
            'student_id' => 'STU-001',
            'cv_url' => null,
        ]);

        $this->expectException(IncompleteProfileException::class);
        $this->expectExceptionMessage('cv_url');

        $this->service->apply($intern, $vacancy->id);
    }

    public function test_apply_throws_when_vacancy_not_open(): void
    {
        $vacancy = Vacancy::factory()->closed()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->complete()->create([
            'user_id' => $intern->id,
            'full_name' => 'Test',
            'institution_name' => 'Univ',
            'major' => 'CS',
            'student_id' => 'STU-001',
            'cv_url' => 'interns/cv/test.pdf',
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Lowongan tidak tersedia.');

        $this->service->apply($intern, $vacancy->id);
    }

    public function test_apply_throws_when_duplicate(): void
    {
        $vacancy = Vacancy::factory()->open()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->complete()->create([
            'user_id' => $intern->id,
            'full_name' => 'Test',
            'institution_name' => 'Univ',
            'major' => 'CS',
            'student_id' => 'STU-001',
            'cv_url' => 'interns/cv/test.pdf',
        ]);

        $this->service->apply($intern, $vacancy->id);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('sudah pernah melamar');

        $this->service->apply($intern, $vacancy->id);
    }

    public function test_apply_throws_when_quota_full(): void
    {
        $vacancy = Vacancy::factory()->open()->create(['quota' => 1]);

        $firstIntern = User::factory()->intern()->create();
        InternProfile::factory()->complete()->create([
            'user_id' => $firstIntern->id,
            'full_name' => 'First',
            'institution_name' => 'Univ',
            'major' => 'CS',
            'student_id' => 'STU-001',
            'cv_url' => 'interns/cv/test.pdf',
        ]);

        $secondIntern = User::factory()->intern()->create();
        InternProfile::factory()->complete()->create([
            'user_id' => $secondIntern->id,
            'full_name' => 'Second',
            'institution_name' => 'Univ',
            'major' => 'CS',
            'student_id' => 'STU-002',
            'cv_url' => 'interns/cv/test.pdf',
        ]);

        \App\Models\Application::factory()->accepted()->create([
            'intern_id' => $firstIntern->id,
            'vacancy_id' => $vacancy->id,
        ]);

        $this->expectException(QuotaFullException::class);

        $this->service->apply($secondIntern, $vacancy->id);
    }

    public function test_accept_creates_internship(): void
    {
        $application = \App\Models\Application::factory()->interviewScheduled()->create();

        $internship = $this->service->accept($application);

        $this->assertEquals('accepted', $application->fresh()->status);
        $this->assertEquals('active', $internship->status);
        $this->assertEquals($application->intern_id, $internship->intern_id);
        $this->assertEquals($application->vacancy_id, $internship->vacancy_id);
        $this->assertEquals($application->id, $internship->application_id);
        $this->assertNull($internship->supervisor_id);
    }

    public function test_reject_updates_status_and_reason(): void
    {
        $application = \App\Models\Application::factory()->underReview()->create();
        $reason = 'Tidak memenuhi kualifikasi';

        $this->service->reject($application, $reason);

        $application->refresh();
        $this->assertEquals('rejected', $application->status);
        $this->assertEquals($reason, $application->rejection_reason);
    }

    public function test_cancel_success(): void
    {
        $application = \App\Models\Application::factory()->submitted()->create();

        $this->service->cancel($application);

        $this->assertEquals('cancelled', $application->fresh()->status);
    }

    public function test_cancel_throws_on_non_submitted(): void
    {
        $application = \App\Models\Application::factory()->underReview()->create();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Hanya lamaran dengan status submitted');

        $this->service->cancel($application);
    }

    public function test_apply_success_when_below_max_active(): void
    {
        $vacancy1 = Vacancy::factory()->open()->create(['quota' => 5]);
        $vacancy2 = Vacancy::factory()->open()->create(['quota' => 5]);
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->complete()->create([
            'user_id' => $intern->id,
            'full_name' => 'Test',
            'institution_name' => 'Univ',
            'major' => 'CS',
            'student_id' => 'STU-001',
            'cv_url' => 'interns/cv/test.pdf',
        ]);

        $this->service->apply($intern, $vacancy1->id);

        $application = $this->service->apply($intern, $vacancy2->id);

        $this->assertEquals('submitted', $application->status);
    }

    public function test_apply_throws_when_already_has_two_active(): void
    {
        $vacancy1 = Vacancy::factory()->open()->create(['quota' => 5]);
        $vacancy2 = Vacancy::factory()->open()->create(['quota' => 5]);
        $vacancy3 = Vacancy::factory()->open()->create(['quota' => 5]);
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->complete()->create([
            'user_id' => $intern->id,
            'full_name' => 'Test',
            'institution_name' => 'Univ',
            'major' => 'CS',
            'student_id' => 'STU-001',
            'cv_url' => 'interns/cv/test.pdf',
        ]);

        $this->service->apply($intern, $vacancy1->id);
        $this->service->apply($intern, $vacancy2->id);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('maksimal 2 lamaran aktif');

        $this->service->apply($intern, $vacancy3->id);
    }

    public function test_apply_success_when_one_rejected(): void
    {
        $vacancy1 = Vacancy::factory()->open()->create(['quota' => 5]);
        $vacancy2 = Vacancy::factory()->open()->create(['quota' => 5]);
        $vacancy3 = Vacancy::factory()->open()->create(['quota' => 5]);
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->complete()->create([
            'user_id' => $intern->id,
            'full_name' => 'Test',
            'institution_name' => 'Univ',
            'major' => 'CS',
            'student_id' => 'STU-001',
            'cv_url' => 'interns/cv/test.pdf',
        ]);

        $app1 = $this->service->apply($intern, $vacancy1->id);
        $app2 = $this->service->apply($intern, $vacancy2->id);

        $this->service->updateStatus($app1, 'under_review');
        $this->service->reject($app1, 'Tidak memenuhi kualifikasi');

        $app3 = $this->service->apply($intern, $vacancy3->id);

        $this->assertEquals('submitted', $app3->status);
    }

    public function test_apply_success_when_one_cancelled(): void
    {
        $vacancy1 = Vacancy::factory()->open()->create(['quota' => 5]);
        $vacancy2 = Vacancy::factory()->open()->create(['quota' => 5]);
        $vacancy3 = Vacancy::factory()->open()->create(['quota' => 5]);
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->complete()->create([
            'user_id' => $intern->id,
            'full_name' => 'Test',
            'institution_name' => 'Univ',
            'major' => 'CS',
            'student_id' => 'STU-001',
            'cv_url' => 'interns/cv/test.pdf',
        ]);

        $app1 = $this->service->apply($intern, $vacancy1->id);
        $app2 = $this->service->apply($intern, $vacancy2->id);

        $this->service->cancel($app1);

        $app3 = $this->service->apply($intern, $vacancy3->id);

        $this->assertEquals('submitted', $app3->status);
    }
}
