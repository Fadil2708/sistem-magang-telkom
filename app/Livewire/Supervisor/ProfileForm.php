<?php

namespace App\Livewire\Supervisor;

use App\Models\SupervisorProfile;
use Livewire\Component;

class SupervisorProfileForm extends Component
{
    public $full_name = '';
    public $employee_id = '';
    public $division = '';
    public $position = '';
    public $phone = '';

    public function mount(): void
    {
        $profile = SupervisorProfile::where('user_id', auth()->id())->first();

        if ($profile) {
            $this->full_name = $profile->full_name ?? '';
            $this->employee_id = $profile->employee_id ?? '';
            $this->division = $profile->division ?? '';
            $this->position = $profile->position ?? '';
            $this->phone = $profile->phone ?? '';
        }
    }

    protected $rules = [
        'full_name' => 'required|string|max:255',
        'employee_id' => 'nullable|string|max:50',
        'division' => 'nullable|string|max:255',
        'position' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
    ];

    public function save(): void
    {
        $this->validate();

        SupervisorProfile::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'full_name' => $this->full_name,
                'employee_id' => $this->employee_id,
                'division' => $this->division,
                'position' => $this->position,
                'phone' => $this->phone,
            ]
        );

        $this->dispatch('toast', message: 'Profil berhasil disimpan.', type: 'success');
    }

    public function render()
    {
        return view('livewire.supervisor.profile-form');
    }
}