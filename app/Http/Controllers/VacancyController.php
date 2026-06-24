<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vacancy\StoreVacancyRequest;
use App\Http\Resources\VacancyResource;
use App\Models\Vacancy;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VacancyController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $user = request()->user();

        $vacancies = Vacancy::with('creator')
            ->when($user->isIntern(), fn($q) => $q
                ->where('status', 'open')
                ->whereDate('application_deadline', '>=', now()->toDateString())
            )
            ->withCount('applications')
            ->orderBy('application_deadline', 'asc')
            ->paginate(15);

        return $this->success(
            VacancyResource::collection($vacancies),
            meta: [
                'current_page' => $vacancies->currentPage(),
                'total' => $vacancies->total(),
            ]
        );
    }

    public function store(StoreVacancyRequest $request): JsonResponse
    {
        $vacancy = Vacancy::create([
            'id' => (string) Str::uuid(),
            'created_by' => $request->user()->id,
            ...$request->validated(),
        ]);

        return $this->success(
            new VacancyResource($vacancy),
            'Lowongan berhasil dibuat.',
            201
        );
    }

    public function show(string $id): JsonResponse
    {
        $vacancy = Vacancy::with('creator')->withCount('applications')->findOrFail($id);

        return $this->success(new VacancyResource($vacancy));
    }

    public function update(StoreVacancyRequest $request, string $id): JsonResponse
    {
        $vacancy = Vacancy::findOrFail($id);
        $vacancy->update($request->validated());

        return $this->success(
            new VacancyResource($vacancy->fresh()->load('creator')->loadCount('applications')),
            'Lowongan berhasil diperbarui.'
        );
    }

    public function destroy(string $id): JsonResponse
    {
        $vacancy = Vacancy::withCount('applications')->findOrFail($id);

        if ($vacancy->applications_count > 0) {
            return $this->error('Lowongan tidak bisa dihapus karena sudah memiliki pelamar.', 422);
        }

        $vacancy->delete();

        return $this->success(null, 'Lowongan berhasil dihapus.');
    }

    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $request->validate(['status' => 'required|in:draft,open,closed']);

        $vacancy = Vacancy::findOrFail($id);
        $from = $vacancy->status;
        $to = $request->status;

        $validTransitions = [
            'draft' => ['open'],
            'open' => ['closed'],
            'closed' => [],
        ];

        if (!in_array($to, $validTransitions[$from] ?? [])) {
            return $this->error("Tidak bisa mengubah status dari {$from} ke {$to}.", 422);
        }

        $vacancy->update(['status' => $to]);

        return $this->success(
            new VacancyResource($vacancy->fresh()),
            'Status lowongan berhasil diperbarui.'
        );
    }
}
