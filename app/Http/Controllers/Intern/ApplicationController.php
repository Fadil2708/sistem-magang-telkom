<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function show(string $id): View
    {
        $application = Application::with([
            'vacancy', 'intern.internProfile', 'internship'
        ])->findOrFail($id);

        abort_if($application->intern_id !== auth()->id(), 403);

        return view('intern.applications.show', compact('application'));
    }
}
