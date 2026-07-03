<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use Illuminate\View\View;

class InternshipController extends Controller
{
    public function index(): View
    {
        $internship = Internship::with([
            'vacancy', 'supervisor.supervisorProfile'
        ])->where('intern_id', auth()->id())->first();

        return view('intern.internship.index', compact('internship'));
    }
}
