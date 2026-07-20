<?php

namespace App\Livewire\Admin;

use App\Models\Vacancy;
use Livewire\Component;

class VacancyForm extends Component
{
    public ?Vacancy $vacancy = null;
    public string $title = '';
    public string $division = '';
    public string $description = '';
    public string $qualifications = '';
    public int $quota = 1;
    public string $start_date = '';
    public string $end_date = '';
    public string $application_deadline = '';
    public string $status = 'draft';

    public bool $isEditing = false;

    public function mount(?string $id = null): void
    {
        if ($id) {
            $this->vacancy = Vacancy::findOrFail($id);
            $this->title = $this->vacancy->title;
            $this->division = $this->vacancy->division;
            $this->description = $this->vacancy->description;
            $this->qualifications = $this->vacancy->qualifications;
            $this->quota = $this->vacancy->quota;
            $this->start_date = $this->vacancy->start_date->format('Y-m-d');
            $this->end_date = $this->vacancy->end_date->format('Y-m-d');
            $this->application_deadline = $this->vacancy->application_deadline->format('Y-m-d');
            $this->status = $this->vacancy->status;
            $this->isEditing = true;
        }
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'division' => 'nullable|string|max:255',
            'description' => 'required|string',
            'qualifications' => 'required|string',
            'quota' => 'required|integer|min:1',
            'start_date' => ['required', 'date', $this->isEditing ? 'nullable' : 'after_or_equal:today'],
            'end_date' => 'required|date|after:start_date',
            'application_deadline' => 'required|date|before:start_date',
            'status' => 'required|in:draft,open,closed',
        ];
    }

    public function save(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $this->validate();

        $data = [
            'created_by' => auth()->id(),
            'title' => $this->title,
            'division' => $this->division,
            'description' => $this->description,
            'qualifications' => $this->qualifications,
            'quota' => $this->quota,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'application_deadline' => $this->application_deadline,
            'status' => $this->status,
        ];

        if ($this->isEditing) {
            $this->vacancy->update($data);
            $this->dispatch('toast', message: 'Lowongan berhasil diperbarui.', type: 'success');
        } else {
            Vacancy::create($data);
            $this->dispatch('toast', message: 'Lowongan berhasil dibuat.', type: 'success');
        }

        $this->redirect(route('admin.vacancies.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.vacancy-form');
    }
}
