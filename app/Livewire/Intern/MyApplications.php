<?php

namespace App\Livewire\Intern;

use App\Models\Application;
use Livewire\Component;
use Livewire\WithPagination;

class MyApplications extends Component
{
    use WithPagination;

    public $filterStatus = '';
    public $confirmingCancelId = null;

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function confirmCancel(string $id): void
    {
        $this->confirmingCancelId = $id;
    }

    public function cancel(): void
    {
        $application = Application::where('intern_id', auth()->id())
            ->where('id', $this->confirmingCancelId)
            ->firstOrFail();

        if ($application->status !== 'submitted') {
            $this->dispatch('toast', message: 'Hanya lamaran dengan status submitted yang dapat dibatalkan.', type: 'error');
            $this->confirmingCancelId = null;
            return;
        }

        $application->update(['status' => 'cancelled']);
        $this->confirmingCancelId = null;
        $this->dispatch('toast', message: 'Lamaran berhasil dibatalkan.', type: 'success');
    }

    public function render()
    {
        $query = Application::where('intern_id', auth()->id())
            ->with('vacancy');

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $applications = $query->orderBy('applied_at', 'desc')->paginate(10);

        return view('livewire.intern.my-applications', compact('applications'));
    }
}
