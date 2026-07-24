<?php

namespace App\Livewire\Admin;

use App\Services\VacancyService;
use Livewire\Component;
use Livewire\WithPagination;

class VacancyList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    private VacancyService $vacancyService;

    public function boot(VacancyService $vacancyService): void
    {
        $this->vacancyService = $vacancyService;
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $vacancies = $this->vacancyService->getPaginatedList(
            $this->search,
            $this->filterStatus,
            $this->sortField,
            $this->sortDirection
        );

        return view('livewire.admin.vacancy-list', compact('vacancies'));
    }
}