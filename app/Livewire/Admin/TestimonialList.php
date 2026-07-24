<?php

namespace App\Livewire\Admin;

use App\Services\TestimonialService;
use Livewire\Component;
use Livewire\WithPagination;

class TestimonialList extends Component
{
    use WithPagination;

    public $filterStatus = '';
    public $confirmingToggleId = null;

    private TestimonialService $testimonialService;

    public function boot(TestimonialService $testimonialService): void
    {
        $this->testimonialService = $testimonialService;
    }

    public function updatingFilterStatus(): void { $this->resetPage(); }

    public function confirmToggle(string $id): void
    {
        $this->confirmingToggleId = $id;
    }

    public function togglePublished(): void
    {
        $testimonial = $this->testimonialService->togglePublished($this->confirmingToggleId);
        $status = $testimonial->is_published ? 'dipublikasikan' : 'ditangguhkan';
        $this->dispatch('toast', message: "Testimonial berhasil {$status}.", type: 'success');
        $this->confirmingToggleId = null;
    }

    public function render()
    {
        $testimonials = $this->testimonialService->getPaginatedList($this->filterStatus);
        return view('livewire.admin.testimonial-list', compact('testimonials'));
    }
}