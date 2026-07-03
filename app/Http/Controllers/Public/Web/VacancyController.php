<?php

namespace App\Http\Controllers\Public\Web;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    public function index(Request $request)
    {
        Vacancy::autoCloseExpired();

        $query = Vacancy::withCount('acceptedApplications')->where('status', 'open')
            ->whereDate('application_deadline', '>=', now()->toDateString())
            ->with('creator');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('division', 'like', "%{$search}%");
            });
        }

        if ($division = $request->get('division')) {
            $query->where('division', $division);
        }

        $vacancies = $query->orderBy('created_at', 'desc')->paginate(12);

        $divisions = Vacancy::where('status', 'open')
            ->whereNotNull('division')
            ->distinct()
            ->orderBy('division')
            ->pluck('division');

        return view('public.vacancies', compact('vacancies', 'divisions'));
    }

    public function show(Vacancy $vacancy)
    {
        return view('public.vacancy-show', compact('vacancy'));
    }
}
