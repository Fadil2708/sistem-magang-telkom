<?php

namespace Tests\Feature\Http;

use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\User;
use Tests\TestCase;

class TestimonialFlowTest extends TestCase
{
    public function test_intern_can_submit_testimonial(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->completed()->create(['intern_id' => $intern->id]);

        $response = $this->actingAs($intern)->postJson('/api/v1/internships/' . $internship->id . '/testimonials', [
            'rating' => 5,
            'content' => 'Great internship experience!',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('testimonials', [
            'intern_id' => $intern->id,
            'rating' => 5,
        ]);
    }

    public function test_intern_cannot_submit_invalid_rating(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->completed()->create(['intern_id' => $intern->id]);

        $response = $this->actingAs($intern)->postJson('/api/v1/internships/' . $internship->id . '/testimonials', [
            'rating' => 10,
            'content' => 'Great experience!',
        ]);

        $response->assertStatus(422);
    }

    public function test_admin_can_toggle_publish(): void
    {
        $admin = User::factory()->admin()->create();
        $testimonial = \App\Models\Testimonial::factory()->unpublished()->create();

        $response = $this->actingAs($admin)->patchJson('/api/v1/testimonials/' . $testimonial->id . '/publish');

        $response->assertStatus(200);
        $this->assertTrue($testimonial->fresh()->is_published);
    }

    public function test_public_can_view_published_testimonials(): void
    {
        \App\Models\Testimonial::factory(3)->published()->create();
        \App\Models\Testimonial::factory()->unpublished()->create();

        $response = $this->getJson('/api/v1/testimonials');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }
}
