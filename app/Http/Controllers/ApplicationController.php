<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ApplicationController extends Controller
{
    use ApiResponse;

    public function show(string $id): JsonResponse
    {
        $user = auth()->user();

        $application = Application::with([
            'intern.internProfile',
            'vacancy',
            'internship.supervisor',
        ])->findOrFail($id);

        if ($user->role === 'admin') {
            // Admin can view any
        } elseif ($user->role === 'supervisor') {
            if ($application->internship?->supervisor_id !== $user->id) {
                return $this->error('Anda tidak berhak melihat lamaran ini.', 403);
            }
        } elseif ($user->role === 'intern') {
            if ($application->intern_id !== $user->id) {
                return $this->error('Anda tidak berhak melihat lamaran ini.', 403);
            }
        } else {
            return $this->error('Unauthorized.', 403);
        }

        return $this->success(new ApplicationResource($application));
    }
}
