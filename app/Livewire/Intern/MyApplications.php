<?php

namespace App\Livewire\Intern;

use App\Models\Application;
use Livewire\Component;
use Livewire\WithPagination;

class MyApplications extends Component
{
    use WithPagination;

    public function render()
    {
        $applications = Application::with(['vacancy', 'internship'])
            ->where('intern_id', auth()->id())
            ->orderBy('applied_at', 'desc')
            ->paginate(10);

        return view('livewire.intern.my-applications', compact('applications'));
    }
}