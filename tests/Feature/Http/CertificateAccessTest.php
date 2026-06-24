<?php

namespace Tests\Feature\Http;

use App\Models\Certificate;
use App\Models\Internship;
use App\Models\User;
use Tests\TestCase;

class CertificateAccessTest extends TestCase
{
    public function test_intern_can_view_own_certificate(): void
    {
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->completed()->create(['intern_id' => $intern->id]);
        Certificate::factory()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        $response = $this->actingAs($intern)->getJson('/api/v1/internships/' . $internship->id . '/certificates');

        $response->assertStatus(200);
    }

    public function test_intern_cannot_view_others_certificate(): void
    {
        $intern = User::factory()->intern()->create();
        $other = User::factory()->intern()->create();
        $internship = Internship::factory()->completed()->create(['intern_id' => $other->id]);
        Certificate::factory()->create([
            'internship_id' => $internship->id,
            'intern_id' => $other->id,
        ]);

        $response = $this->actingAs($intern)->getJson('/api/v1/internships/' . $internship->id . '/certificates');

        $response->assertStatus(403);
    }

    public function test_supervisor_can_view_own_intern_certificate(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->completed()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        Certificate::factory()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        $response = $this->actingAs($supervisor)->getJson('/api/v1/internships/' . $internship->id . '/certificates');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_any_certificate(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->completed()->create(['intern_id' => $intern->id]);
        Certificate::factory()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        $response = $this->actingAs($admin)->getJson('/api/v1/internships/' . $internship->id . '/certificates');

        $response->assertStatus(200);
    }

    public function test_intern_download_own_certificate_not_found(): void
    {
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->completed()->create(['intern_id' => $intern->id]);
        $certificate = Certificate::factory()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
            'certificate_file_url' => null,
        ]);

        $response = $this->actingAs($intern)->getJson('/api/v1/certificates/' . $certificate->id . '/download');

        $response->assertStatus(404);
    }

    public function test_intern_cannot_download_others_certificate(): void
    {
        $intern = User::factory()->intern()->create();
        $other = User::factory()->intern()->create();
        $internship = Internship::factory()->completed()->create(['intern_id' => $other->id]);
        $certificate = Certificate::factory()->create([
            'internship_id' => $internship->id,
            'intern_id' => $other->id,
        ]);

        $response = $this->actingAs($intern)->getJson('/api/v1/certificates/' . $certificate->id . '/download');

        $response->assertStatus(404);
    }
}
