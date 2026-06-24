<?php

namespace App\Livewire\Supervisor;

use App\Models\SupervisorProfile;
use Livewire\Component;

class ProfileForm extends Component
{
    public $full_name = '';
    public $employee_id = '';
    public $division = '';
    public $position = '';
    public $phone = '';

    public function mount(): void
    {
        $profile = auth()->user()->supervisorProfile;

        if ($profile) {
            $this->full_name = $profile->full_name;
            $this->employee_id = $profile->employee_id;
            $this->division = $profile->division;
            $this->position = $profile->position;
            $this->phone = $profile->phone;
        }
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'employee_id' => 'nullable|string|max:100',
            'division' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ];
    }

    public function save(): void
    {
        $this->validate();

        SupervisorProfile::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'full_name' => $this->full_name,
                'employee_id' => $this->employee_id ?: null,
                'division' => $this->division ?: null,
                'position' => $this->position ?: null,
                'phone' => $this->phone ?: null,
            ]
        );

        session()->flash('success', 'Profil berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.supervisor.profile-form');
    }
}
