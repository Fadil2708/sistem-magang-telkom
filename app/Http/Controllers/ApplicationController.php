<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ApplicationController extends Controller
{
    use ApiResponse;

    public function show(string $id): JsonResponse
    {
        $application = Application::with([
            'intern.internProfile',
            'vacancy',
            'internship.supervisor',
        ])->findOrFail($id);

        Gate::authorize('view', $application);

        return $this->success(new ApplicationResource($application));
    }
}
