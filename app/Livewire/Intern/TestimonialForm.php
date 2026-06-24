<?php

namespace App\Livewire\Intern;

use App\Models\Internship;
use App\Models\Testimonial;
use Livewire\Component;

class TestimonialForm extends Component
{
    public int $rating = 5;
    public string $content = '';
    public ?Internship $internship = null;
    public ?Testimonial $testimonial = null;
    public bool $hasCompletedInternship = false;
    public bool $alreadySubmitted = false;

    protected function rules(): array
    {
        return [
            'rating'  => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:20|max:1000',
        ];
    }

    public function mount(): void
    {
        $this->internship = Internship::where('intern_id', auth()->id())
            ->where('status', 'completed')
            ->latest()
            ->first();

        $this->hasCompletedInternship = $this->internship !== null;

        if ($this->internship) {
            $this->testimonial = Testimonial::where('internship_id', $this->internship->id)->first();
            $this->alreadySubmitted = $this->testimonial !== null;

            if ($this->testimonial) {
                $this->rating = $this->testimonial->rating;
                $this->content = $this->testimonial->content;
            }
        }
    }

    public function save(): void
    {
        $this->validate();

        $internship = Internship::where('intern_id', auth()->id())
            ->where('status', 'completed')
            ->latest()
            ->firstOrFail();

        Testimonial::updateOrCreate(
            ['internship_id' => $internship->id],
            [
                'intern_id' => auth()->id(),
                'rating' => $this->rating,
                'content' => $this->content,
                'is_published' => false,
            ]
        );

        session()->flash('success', 'Testimoni berhasil dikirim dan menunggu persetujuan admin.');
        $this->alreadySubmitted = true;
    }

    public function render()
    {
        return view('livewire.intern.testimonial-form');
    }
}
