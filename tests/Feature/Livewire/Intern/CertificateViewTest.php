<?php

namespace Tests\Feature\Livewire\Intern;

use App\Livewire\Intern\CertificateView;
use App\Models\Certificate;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class CertificateViewTest extends TestCase
{
    public function test_mount_without_completed_internship(): void
    {
        $intern = User::factory()->intern()->create();

        Livewire::actingAs($intern)
            ->test(CertificateView::class)
            ->assertSet('hasCompletedInternship', false);
    }

    public function test_mount_with_completed_internship(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->completed()->create(['intern_id' => $intern->id]);
        Certificate::factory()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        Livewire::actingAs($intern)
            ->test(CertificateView::class)
            ->assertSet('hasCompletedInternship', true);
    }

    public function test_mount_without_certificate(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        Internship::factory()->completed()->create(['intern_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(CertificateView::class)
            ->assertSet('certificate', null);
    }
}
