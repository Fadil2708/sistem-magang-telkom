<?php

namespace App\Livewire\Intern;

use App\Models\InternProfile;
use App\Models\Skill;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileForm extends Component
{
    use WithFileUploads;

    public $full_name = '';
    public $gender = '';
    public $phone = '';
    public $address = '';
    public $date_of_birth = '';
    public $institution_name = '';
    public $institution_type = '';
    public $major = '';
    public $student_id = '';
    public $photo;
    public $cv;
    public $cover_letter;
    public $selectedSkills = [];

    public $existingPhoto = null;
    public $existingCv = null;
    public $existingCoverLetter = null;
    public $allSkills = [];

    public function mount(): void
    {
        $profile = InternProfile::where('user_id', auth()->id())->first();
        $this->allSkills = Skill::orderBy('name')->get();

        if ($profile) {
            $this->full_name = $profile->full_name ?? '';
            $this->gender = $profile->gender ?? '';
            $this->phone = $profile->phone ?? '';
            $this->address = $profile->address ?? '';
            $this->date_of_birth = $profile->date_of_birth?->format('Y-m-d') ?? '';
            $this->institution_name = $profile->institution_name ?? '';
            $this->institution_type = $profile->institution_type ?? '';
            $this->major = $profile->major ?? '';
            $this->student_id = $profile->student_id ?? '';
            $this->existingPhoto = $profile->photo_url;
            $this->existingCv = $profile->cv_url;
            $this->existingCoverLetter = $profile->cover_letter_url;
            $this->selectedSkills = $profile->skills->pluck('id')->map(fn($id) => (string) $id)->toArray();
        }
    }

    public function save(): void
    {
        $this->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'nullable|in:L,P',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'institution_name' => 'required|string|max:255',
            'institution_type' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'student_id' => 'nullable|string|max:50',
            'photo' => 'nullable|image|max:2048',
            'cv' => 'nullable|file|mimes:pdf|max:5120',
            'cover_letter' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $data = [
            'user_id' => auth()->id(),
            'full_name' => $this->full_name,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'address' => $this->address,
            'date_of_birth' => $this->date_of_birth ?: null,
            'institution_name' => $this->institution_name,
            'institution_type' => $this->institution_type,
            'major' => $this->major,
            'student_id' => $this->student_id,
        ];

        if ($this->photo) {
            $data['photo_url'] = $this->photo->store('photos', 'public');
        }
        if ($this->cv) {
            $data['cv_url'] = $this->cv->store('cvs', 'public');
        }
        if ($this->cover_letter) {
            $data['cover_letter_url'] = $this->cover_letter->store('cover-letters', 'public');
        }

        $profile = InternProfile::updateOrCreate(['user_id' => auth()->id()], $data);

        if ($this->selectedSkills) {
            $profile->skills()->sync($this->selectedSkills);
        }

        $this->dispatch('toast', message: 'Profil berhasil disimpan.', type: 'success');
    }

    public function render()
    {
        return view('livewire.intern.profile-form');
    }
}