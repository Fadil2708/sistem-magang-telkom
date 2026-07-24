<?php

namespace App\Livewire\Intern;

use Livewire\Component;

class TestimonialForm extends Component
{
    public int $rating = 5;
    public string $content = '';
    public bool $submitted = false;

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'content' => 'required|string|max:1000',
    ];

    public function submit(): void
    {
        $this->validate();

        $internship = \App\Models\Internship::where('intern_id', auth()->id())
            ->where('status', 'completed')
            ->latest()
            ->firstOrFail();

        \App\Models\Testimonial::create([
            'intern_id' => auth()->id(),
            'internship_id' => $internship->id,
            'rating' => $this->rating,
            'content' => $this->content,
        ]);

        $this->submitted = true;
        $this->dispatch('toast', message: 'Testimonial berhasil dikirim.', type: 'success');
    }

    public function render()
    {
        return view('livewire.intern.testimonial-form');
    }
}