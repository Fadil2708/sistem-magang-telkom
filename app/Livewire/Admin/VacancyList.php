<?php

namespace App\Livewire\Admin;

use App\Models\Vacancy;
use Livewire\Component;
use Livewire\WithPagination;

class VacancyList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function deleteVacancy(string $id): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $vacancy = Vacancy::findOrFail($id);

        if ($vacancy->applications()->exists()) {
            $this->dispatch('toast', message: 'Lowongan tidak bisa dihapus karena sudah memiliki pelamar.', type: 'error');
            return;
        }

        $vacancy->delete();
        $this->dispatch('toast', message: 'Lowongan berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        Vacancy::autoCloseExpired();

        $vacancies = Vacancy::query()
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.vacancy-list', compact('vacancies'));
    }
}
