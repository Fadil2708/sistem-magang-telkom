<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\TestimonialList;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\Testimonial;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class TestimonialListTest extends TestCase
{
    public function test_confirm_toggle_sets_id(): void
    {
        $admin = User::factory()->admin()->create();
        $testimonial = Testimonial::factory()->create();

        Livewire::actingAs($admin)
            ->test(TestimonialList::class)
            ->call('confirmToggle', $testimonial->id)
            ->assertSet('confirmingToggleId', $testimonial->id);
    }

    public function test_can_toggle_publish(): void
    {
        $admin = User::factory()->admin()->create();
        $testimonial = Testimonial::factory()->create(['is_published' => false]);

        Livewire::actingAs($admin)
            ->test(TestimonialList::class)
            ->call('confirmToggle', $testimonial->id)
            ->call('togglePublish');

        $this->assertTrue($testimonial->fresh()->is_published);
    }

    public function test_can_filter_by_status(): void
    {
        $admin = User::factory()->admin()->create();
        Testimonial::factory()->create(['is_published' => true]);
        Testimonial::factory()->create(['is_published' => false]);

        Livewire::actingAs($admin)
            ->test(TestimonialList::class)
            ->set('filterStatus', 'published')
            ->assertSet('filterStatus', 'published');
    }
}
