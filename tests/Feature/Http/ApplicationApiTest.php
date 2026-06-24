<?php

namespace Tests\Feature\Http;

use App\Models\Application;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApplicationApiTest extends TestCase
{
    public function test_admin_can_list_applications(): void
    {
        $admin = User::factory()->admin()->create();
        Application::factory()->submitted()->count(3)->create();

        $response = $this->actingAs($admin)->getJson('/api/v1/applications');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_admin_can_filter_applications_by_status(): void
    {
        $admin = User::factory()->admin()->create();
        Application::factory()->submitted()->create();
        Application::factory()->accepted()->create();

        $response = $this->actingAs($admin)->getJson('/api/v1/applications?status=accepted');

        $response->assertStatus(200);
    }

    public function test_intern_cannot_list_applications(): void
    {
        $intern = User::factory()->intern()->create();

        $response = $this->actingAs($intern)->getJson('/api/v1/applications');

        $response->assertStatus(403);
    }

    public function test_intern_can_view_own_application(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $application = Application::factory()->submitted()->create(['intern_id' => $intern->id]);

        $response = $this->actingAs($intern)->getJson('/api/v1/applications/' . $application->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $application->id);
    }

    public function test_intern_cannot_view_others_application(): void
    {
        $intern = User::factory()->intern()->create();
        $other = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $other->id]);
        $application = Application::factory()->submitted()->create(['intern_id' => $other->id]);

        $response = $this->actingAs($intern)->getJson('/api/v1/applications/' . $application->id);

        $response->assertStatus(403);
    }

    public function test_admin_can_view_any_application(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $application = Application::factory()->submitted()->create(['intern_id' => $intern->id]);

        $response = $this->actingAs($admin)->getJson('/api/v1/applications/' . $application->id);

        $response->assertStatus(200);
    }

    public function test_supervisor_cannot_view_unrelated_application(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $application = Application::factory()->submitted()->create(['intern_id' => $intern->id]);

        $response = $this->actingAs($supervisor)->getJson('/api/v1/applications/' . $application->id);

        $response->assertStatus(403);
    }

    public function test_intern_can_view_my_applications(): void
    {
        $intern = User::factory()->intern()->create();
        Application::factory()->submitted()->count(2)->create(['intern_id' => $intern->id]);

        $response = $this->actingAs($intern)->getJson('/api/v1/applications/my');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_supervisor_cannot_access_my_applications(): void
    {
        $supervisor = User::factory()->supervisor()->create();

        $response = $this->actingAs($supervisor)->getJson('/api/v1/applications/my');

        $response->assertStatus(403);
    }

    public function test_admin_can_download_cv(): void
    {
        Storage::fake('private');
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        $profile = InternProfile::factory()->create([
            'user_id' => $intern->id,
            'cv_url' => 'interns/cv/test.pdf',
        ]);
        Storage::disk('private')->put('interns/cv/test.pdf', 'fake content');
        $application = Application::factory()->submitted()->create(['intern_id' => $intern->id]);

        $response = $this->actingAs($admin)->get('/admin/applications/' . $application->id . '/file/cv');

        $response->assertStatus(200);
    }

    public function test_admin_cannot_download_non_existent_file(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create([
            'user_id' => $intern->id,
            'cv_url' => null,
        ]);
        $application = Application::factory()->submitted()->create(['intern_id' => $intern->id]);

        $response = $this->actingAs($admin)->get('/admin/applications/' . $application->id . '/file/cv');

        $response->assertStatus(404);
    }

    public function test_admin_cannot_download_invalid_file_type(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $application = Application::factory()->submitted()->create(['intern_id' => $intern->id]);

        $response = $this->actingAs($admin)->get('/admin/applications/' . $application->id . '/file/invalid');

        $response->assertStatus(404);
    }
}
