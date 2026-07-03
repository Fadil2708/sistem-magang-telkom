<?php

namespace App\Livewire\Intern;

use App\Models\Vacancy;
use Livewire\Component;
use Livewire\WithPagination;

class VacancyList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterDivision = '';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterDivision(): void { $this->resetPage(); }

    public function render()
    {
        $vacancies = Vacancy::withCount('acceptedApplications')->where('status', 'open')
            ->where('application_deadline', '>=', now()->format('Y-m-d'))
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->filterDivision, fn($q) => $q->where('division', $this->filterDivision))
            ->orderBy('application_deadline', 'asc')
            ->paginate(10);

        $divisions = Vacancy::where('status', 'open')->select('division')->distinct()->pluck('division');

        return view('livewire.intern.vacancy-list', compact('vacancies', 'divisions'));
    }
}
