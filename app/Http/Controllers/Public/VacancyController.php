<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\VacancyResource;
use App\Models\Vacancy;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class VacancyController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $vacancies = Vacancy::where('status', 'open')
            ->whereDate('application_deadline', '>=', now()->toDateString())
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return $this->success(
            VacancyResource::collection($vacancies),
            meta: [
                'current_page' => $vacancies->currentPage(),
                'total' => $vacancies->total(),
            ]
        );
    }

    public function show(string $id): JsonResponse
    {
        $vacancy = Vacancy::where('status', 'open')
            ->with('creator')
            ->findOrFail($id);

        return $this->success(new VacancyResource($vacancy));
    }
}
