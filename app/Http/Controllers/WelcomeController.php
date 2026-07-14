<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Internship;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\Vacancy;

class WelcomeController extends Controller
{
    public function __invoke()
    {
        $vacancies = Vacancy::where('status', 'open')
            ->whereDate('application_deadline', '>=', now()->toDateString())
            ->latest()
            ->take(4)
            ->get();

        $stats = [
            'interns' => User::where('role', 'intern')->count(),
            'supervisors' => User::where('role', 'supervisor')->count(),
            'completed' => Internship::where('status', 'completed')->count(),
        ];

        $testimonials = Testimonial::published()
            ->with('intern.internProfile')
            ->latest()
            ->take(3)
            ->get();

        $faqs = Faq::active()->ordered()->get();

        return view('welcome', compact('vacancies', 'stats', 'testimonials', 'faqs'));
    }
}
