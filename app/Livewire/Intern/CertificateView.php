<?php

namespace App\Livewire\Intern;

use App\Models\Certificate;
use App\Models\Evaluation;
use Livewire\Component;

class CertificateView extends Component
{
    public $certificate = null;
    public $evaluation = null;

    public function mount(): void
    {
        $this->certificate = Certificate::with(['intern.internProfile', 'issuedBy'])
            ->where('intern_id', auth()->id())
            ->latest()
            ->first();

        if ($this->certificate) {
            $this->evaluation = Evaluation::where('internship_id', $this->certificate->internship_id)
                ->with('supervisor.supervisorProfile')
                ->first();
        }
    }

    public function render()
    {
        return view('livewire.intern.certificate-view');
    }
}