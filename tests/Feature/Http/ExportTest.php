<?php

namespace Tests\Feature\Http;

use App\Models\Application;
use App\Models\Certificate;
use App\Models\Evaluation;
use App\Models\FinalReport;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\Logbook;
use App\Models\SupervisorProfile;
use App\Models\User;
use App\Models\Vacancy;
use Tests\TestCase;

class ExportTest extends TestCase
{
    private User $admin;
    private Vacancy $vacancy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->vacancy = Vacancy::factory()->open()->create(['created_by' => $this->admin->id]);
    }

    public function test_export_internships(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id, 'full_name' => 'Budi Intern']);
        $supervisor = User::factory()->supervisor()->create();
        SupervisorProfile::factory()->create(['user_id' => $supervisor->id, 'full_name' => 'Pembimbing Satu']);
        $app = Application::factory()->create(['intern_id' => $intern->id, 'vacancy_id' => $this->vacancy->id]);
        Internship::create([
            'application_id' => $app->id, 'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id, 'vacancy_id' => $this->vacancy->id,
            'status' => 'active', 'actual_start_date' => now(), 'actual_end_date' => now()->addMonths(3),
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.export.internships'));

        $response->assertOk();
        $response->assertDownload('internships.xlsx');
    }

    public function test_export_logbooks(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id, 'full_name' => 'Budi Intern']);
        $app = Application::factory()->accepted()->create(['intern_id' => $intern->id, 'vacancy_id' => $this->vacancy->id]);
        $is = Internship::create([
            'application_id' => $app->id, 'intern_id' => $intern->id,
            'supervisor_id' => User::factory()->supervisor()->create()->id,
            'vacancy_id' => $this->vacancy->id, 'status' => 'active',
        ]);
        Logbook::factory()->create(['internship_id' => $is->id, 'intern_id' => $intern->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.export.logbooks'));

        $response->assertOk();
        $response->assertDownload('logbooks.xlsx');
    }

    public function test_export_applications(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id, 'full_name' => 'Budi Intern']);
        Application::factory()->create(['intern_id' => $intern->id, 'vacancy_id' => $this->vacancy->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.export.applications'));

        $response->assertOk();
        $response->assertDownload('applications.xlsx');
    }

    public function test_export_reports(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id, 'full_name' => 'Budi Intern']);
        $app = Application::factory()->accepted()->create(['intern_id' => $intern->id, 'vacancy_id' => $this->vacancy->id]);
        $is = Internship::create([
            'application_id' => $app->id, 'intern_id' => $intern->id,
            'supervisor_id' => User::factory()->supervisor()->create()->id,
            'vacancy_id' => $this->vacancy->id, 'status' => 'active',
        ]);
        FinalReport::factory()->create(['internship_id' => $is->id, 'intern_id' => $intern->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.export.reports'));

        $response->assertOk();
        $response->assertDownload('reports.xlsx');
    }

    public function test_export_evaluations(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id, 'full_name' => 'Budi Intern']);
        $supervisor = User::factory()->supervisor()->create();
        SupervisorProfile::factory()->create(['user_id' => $supervisor->id, 'full_name' => 'Pembimbing Satu']);
        $app = Application::factory()->accepted()->create(['intern_id' => $intern->id, 'vacancy_id' => $this->vacancy->id]);
        $is = Internship::create([
            'application_id' => $app->id, 'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id, 'vacancy_id' => $this->vacancy->id,
            'status' => 'completed',
        ]);
        $evaluation = Evaluation::factory()->create([
            'internship_id' => $is->id, 'supervisor_id' => $supervisor->id,
        ]);
        $evaluation->calculateFinalScore();
        $evaluation->save();

        $response = $this->actingAs($this->admin)->get(route('admin.export.evaluations'));

        $response->assertOk();
        $response->assertDownload('evaluations.xlsx');
    }

    public function test_export_certificates(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id, 'full_name' => 'Budi Intern']);
        $supervisor = User::factory()->supervisor()->create();
        SupervisorProfile::factory()->create(['user_id' => $supervisor->id, 'full_name' => 'Pembimbing Satu']);
        $app = Application::factory()->accepted()->create(['intern_id' => $intern->id, 'vacancy_id' => $this->vacancy->id]);
        $is = Internship::create([
            'application_id' => $app->id, 'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id, 'vacancy_id' => $this->vacancy->id,
            'status' => 'completed',
        ]);
        Certificate::factory()->create([
            'internship_id' => $is->id, 'intern_id' => $intern->id, 'issued_by' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.export.certificates'));

        $response->assertOk();
        $response->assertDownload('certificates.xlsx');
    }

    public function test_non_admin_cannot_export(): void
    {
        $intern = User::factory()->intern()->create();

        $response = $this->actingAs($intern)->get(route('admin.export.internships'));

        $response->assertRedirectToRoute('intern.dashboard');
    }
}
