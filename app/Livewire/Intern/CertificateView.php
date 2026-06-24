<?php

namespace App\Livewire\Intern;

use App\Models\Certificate;
use App\Models\Internship;
use Livewire\Component;

class CertificateView extends Component
{
    public ?Certificate $certificate = null;
    public ?Internship $internship = null;
    public bool $hasCompletedInternship = false;

    public function mount(): void
    {
        $this->internship = Internship::where('intern_id', auth()->id())
            ->where('status', 'completed')
            ->latest()
            ->first();

        $this->hasCompletedInternship = $this->internship !== null;

        if ($this->internship) {
            $this->certificate = Certificate::where('internship_id', $this->internship->id)->first();
        }
    }

    public function render()
    {
        return view('livewire.intern.certificate-view');
    }
}
