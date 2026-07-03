<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InternController extends Controller
{
    public function show(string $id): View
    {
        $internship = Internship::with(['intern.internProfile', 'vacancy'])
            ->findOrFail($id);

        abort_if($internship->supervisor_id === null || $internship->supervisor_id !== auth()->id(), 403);

        return view('supervisor.interns.show', compact('internship'));
    }
}
