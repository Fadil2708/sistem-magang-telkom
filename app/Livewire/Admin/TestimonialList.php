<?php

namespace App\Livewire\Admin;

use App\Models\Testimonial;
use Livewire\Component;
use Livewire\WithPagination;

class TestimonialList extends Component
{
    use WithPagination;

    public $filterStatus = '';
    public $confirmingToggleId = null;

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function confirmToggle(string $id): void
    {
        $this->confirmingToggleId = $id;
    }

    public function togglePublish(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $testimonial = Testimonial::findOrFail($this->confirmingToggleId);
        $testimonial->update(['is_published' => !$testimonial->is_published]);

        $status = $testimonial->fresh()->is_published ? 'ditayangkan' : 'disembunyikan';
        $this->dispatch('toast', message: "Testimoni berhasil {$status}.", type: 'success');
        $this->confirmingToggleId = null;
    }

    public function render()
    {
        $testimonials = Testimonial::with(['intern.internProfile', 'internship.vacancy'])
            ->when($this->filterStatus === 'published', fn($q) => $q->where('is_published', true))
            ->when($this->filterStatus === 'pending', fn($q) => $q->where('is_published', false))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.testimonial-list', compact('testimonials'));
    }
}
