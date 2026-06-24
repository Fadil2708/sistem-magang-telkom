<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Vacancy;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    public function test_created_creates_audit_log(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        $vacancy = Vacancy::factory()->create(['created_by' => $admin->id]);

        $this->actingAs($admin);

        $application = Application::factory()->create([
            'intern_id' => $intern->id,
            'vacancy_id' => $vacancy->id,
        ]);

        $log = AuditLog::where('auditable_id', $application->id)->first();

        $this->assertNotNull($log);
        $this->assertEquals('created', $log->action);
        $this->assertEquals(Application::class, $log->auditable_type);
        $this->assertEquals($admin->id, $log->user_id);
        $this->assertNotEmpty($log->new_values);
    }

    public function test_updated_creates_audit_log_with_changes(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        $vacancy = Vacancy::factory()->create(['created_by' => $admin->id]);

        $this->actingAs($admin);

        $application = Application::factory()->create([
            'intern_id' => $intern->id,
            'vacancy_id' => $vacancy->id,
            'status' => 'submitted',
        ]);

        $application->update(['status' => 'under_review']);

        $logs = AuditLog::where('auditable_id', $application->id)
            ->orderBy('created_at')
            ->get();

        $this->assertCount(2, $logs);

        $updateLog = $logs->last();
        $this->assertEquals('updated', $updateLog->action);
        $this->assertEquals(['status' => 'submitted'], $updateLog->old_values);
        $this->assertEquals(['status' => 'under_review'], $updateLog->new_values);
    }

    public function test_deleted_creates_audit_log(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        $vacancy = Vacancy::factory()->create(['created_by' => $admin->id]);

        $this->actingAs($admin);

        $application = Application::factory()->create([
            'intern_id' => $intern->id,
            'vacancy_id' => $vacancy->id,
        ]);

        $application->delete();

        $log = AuditLog::where('auditable_id', $application->id)
            ->where('action', 'deleted')
            ->first();

        $this->assertNotNull($log);
        $this->assertNotEmpty($log->old_values);
    }

    public function test_audit_log_stores_null_user_when_unauthenticated(): void
    {
        $intern = User::factory()->intern()->create();
        $vacancy = Vacancy::factory()->create();

        $application = Application::factory()->create([
            'intern_id' => $intern->id,
            'vacancy_id' => $vacancy->id,
        ]);

        $log = AuditLog::where('auditable_id', $application->id)->first();

        $this->assertNull($log->user_id);
    }

    public function test_audit_log_stores_ip_address(): void
    {
        $intern = User::factory()->intern()->create();
        $vacancy = Vacancy::factory()->create();

        $application = Application::factory()->create([
            'intern_id' => $intern->id,
            'vacancy_id' => $vacancy->id,
        ]);

        $log = AuditLog::where('auditable_id', $application->id)->first();

        $this->assertNotNull($log->ip_address);
    }

    public function test_audit_logs_morph_relationship(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        $vacancy = Vacancy::factory()->create(['created_by' => $admin->id]);

        $this->actingAs($admin);

        $application = Application::factory()->create([
            'intern_id' => $intern->id,
            'vacancy_id' => $vacancy->id,
        ]);

        $this->assertCount(1, $application->auditLogs);
        $this->assertEquals($application->id, $application->auditLogs->first()->auditable_id);
    }

    public function test_only_changed_attributes_in_updated_audit(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        $vacancy = Vacancy::factory()->create(['created_by' => $admin->id]);

        $this->actingAs($admin);

        $application = Application::factory()->create([
            'intern_id' => $intern->id,
            'vacancy_id' => $vacancy->id,
            'status' => 'submitted',
            'admin_notes' => null,
        ]);

        $application->update(['admin_notes' => 'Catatan dari admin']);

        $logs = AuditLog::where('auditable_id', $application->id)
            ->where('action', 'updated')
            ->first();

        $this->assertArrayHasKey('admin_notes', $logs->new_values);
        $this->assertArrayNotHasKey('intern_id', $logs->new_values);
    }
}
