<?php

namespace App\Livewire\Intern;

use App\Models\InternProfile;
use App\Models\Skill;
use App\Services\FileUploadService;
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

    public $existing_photo_url = '';
    public $existing_cv_url = '';
    public $existing_cover_letter_url = '';

    public $photo_url = '';
    public $cv_url = '';
    public $cover_letter_url = '';

    public array $selectedSkills = [];

    public bool $isEditing = false;
    public bool $hasProfile = false;

    public function mount(): void
    {
        $profile = auth()->user()->internProfile;

        if ($profile) {
            $this->full_name = $profile->full_name;
            $this->gender = $profile->gender;
            $this->selectedSkills = $profile->skills->pluck('id')->toArray();
            $this->phone = $profile->phone;
            $this->address = $profile->address;
            $this->date_of_birth = $profile->date_of_birth?->format('Y-m-d') ?? '';
            $this->institution_name = $profile->institution_name;
            $this->institution_type = $profile->institution_type;
            $this->major = $profile->major;
            $this->student_id = $profile->student_id;
            $this->existing_photo_url = $profile->photo_url;
            $this->existing_cv_url = $profile->cv_url;
            $this->existing_cover_letter_url = $profile->cover_letter_url;
            $this->photo_url = $profile->photo_url ? url('private/' . $profile->photo_url) : '';
            $this->cv_url = $profile->cv_url ? url('private/' . $profile->cv_url) : '';
            $this->cover_letter_url = $profile->cover_letter_url ? url('private/' . $profile->cover_letter_url) : '';
        }

        $this->hasProfile = $profile && !empty($profile->full_name);
        $this->isEditing = !$this->hasProfile;
    }

    public function cancelEdit(): void
    {
        $this->isEditing = false;
        $this->reset('photo', 'cv', 'cover_letter');
        $this->resetValidation();
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date|before:today',
            'institution_name' => 'required|string|max:255',
            'institution_type' => 'required|in:university,vocational,highschool',
            'major' => 'required|string|max:255',
            'student_id' => 'required|string|max:100',
            'photo' => 'nullable|image|mimetypes:image/jpeg,image/png|max:2048',
            'cv' => 'nullable|file|mimetypes:application/pdf|max:5120',
            'cover_letter' => 'nullable|file|mimetypes:application/pdf|max:5120',
        ];
    }

    public function save(FileUploadService $uploadService): void
    {
        $this->validate();

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
            if ($this->existing_photo_url) {
                $uploadService->delete($this->existing_photo_url);
            }
            $url = $uploadService->uploadProfilePhoto($this->photo, auth()->id());
            if (!$url) {
                session()->flash('error', 'Gagal mengupload foto profil.');
                return;
            }
            $data['photo_url'] = $url;
        }

        if ($this->cv) {
            if ($this->existing_cv_url) {
                $uploadService->delete($this->existing_cv_url);
            }
            $url = $uploadService->uploadCv($this->cv, auth()->id());
            if (!$url) {
                session()->flash('error', 'Gagal mengupload CV.');
                return;
            }
            $data['cv_url'] = $url;
        }

        if ($this->cover_letter) {
            if ($this->existing_cover_letter_url) {
                $uploadService->delete($this->existing_cover_letter_url);
            }
            $url = $uploadService->uploadCoverLetter($this->cover_letter, auth()->id());
            if (!$url) {
                session()->flash('error', 'Gagal mengupload cover letter.');
                return;
            }
            $data['cover_letter_url'] = $url;
        }

        $profile = InternProfile::updateOrCreate(['user_id' => auth()->id()], $data);
        $profile->skills()->sync($this->selectedSkills);
        $this->existing_photo_url = $profile->photo_url;
        $this->existing_cv_url = $profile->cv_url;
        $this->existing_cover_letter_url = $profile->cover_letter_url;
        $this->photo_url = $profile->photo_url ? url('private/' . $profile->photo_url) : '';
        $this->cv_url = $profile->cv_url ? url('private/' . $profile->cv_url) : '';
        $this->cover_letter_url = $profile->cover_letter_url ? url('private/' . $profile->cover_letter_url) : '';
        $this->selectedSkills = $profile->skills->pluck('id')->toArray();
        $this->isEditing = false;
        $this->hasProfile = true;
        session()->flash('success', 'Profil berhasil disimpan.');
    }

    public function render()
    {
        $allSkills = Skill::orderBy('category')->orderBy('name')->get()->groupBy('category');
        $skillsList = Skill::whereIn('id', $this->selectedSkills)->get();

        return view('livewire.intern.profile-form', compact('allSkills', 'skillsList'));
    }
}
