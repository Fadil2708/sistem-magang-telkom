<?php

namespace App\Livewire\Intern;

use App\Services\VacancyService;
use Livewire\Component;
use Livewire\WithPagination;

class VacancyList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterDivision = '';

    private VacancyService $vacancyService;

    public function boot(VacancyService $vacancyService): void
    {
        $this->vacancyService = $vacancyService;
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterDivision(): void { $this->resetPage(); }

    public function render()
    {
        $vacancies = $this->vacancyService->getOpenVacancies($this->search, $this->filterDivision);
        return view('livewire.intern.vacancy-list', compact('vacancies'));
    }
}