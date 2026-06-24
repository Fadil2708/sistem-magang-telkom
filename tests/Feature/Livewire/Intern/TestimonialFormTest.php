<?php

namespace Tests\Feature\Livewire\Intern;

use App\Livewire\Intern\TestimonialForm;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\Testimonial;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class TestimonialFormTest extends TestCase
{
    public function test_mount_without_completed_internship(): void
    {
        $intern = User::factory()->intern()->create();

        Livewire::actingAs($intern)
            ->test(TestimonialForm::class)
            ->assertSet('hasCompletedInternship', false);
    }

    public function test_mount_with_completed_internship(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        Internship::factory()->completed()->create(['intern_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(TestimonialForm::class)
            ->assertSet('hasCompletedInternship', true)
            ->assertSet('alreadySubmitted', false);
    }

    public function test_can_submit_testimonial(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->completed()->create(['intern_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(TestimonialForm::class)
            ->set('rating', 4)
            ->set('content', 'Pengalaman magang yang sangat berharga dan menyenangkan.')
            ->call('save')
            ->assertSet('alreadySubmitted', true);

        $this->assertDatabaseHas('testimonials', [
            'internship_id' => $internship->id,
            'rating' => 4,
            'is_published' => false,
        ]);
    }

    public function test_validation_fails_short_content(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        Internship::factory()->completed()->create(['intern_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(TestimonialForm::class)
            ->set('rating', 5)
            ->set('content', 'Short')
            ->call('save')
            ->assertHasErrors(['content']);
    }

    public function test_validation_fails_invalid_rating(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        Internship::factory()->completed()->create(['intern_id' => $intern->id]);

        Livewire::actingAs($intern)
            ->test(TestimonialForm::class)
            ->set('rating', 0)
            ->set('content', 'Pengalaman magang yang sangat berharga dan menyenangkan.')
            ->call('save')
            ->assertHasErrors(['rating']);
    }

    public function test_already_submitted_shows_existing(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->completed()->create(['intern_id' => $intern->id]);
        Testimonial::factory()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
            'rating' => 3,
        ]);

        Livewire::actingAs($intern)
            ->test(TestimonialForm::class)
            ->assertSet('alreadySubmitted', true)
            ->assertSet('rating', 3);
    }
}
