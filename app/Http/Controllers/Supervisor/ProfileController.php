<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateSupervisorProfileRequest;
use App\Http\Resources\SupervisorProfileResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    use ApiResponse;

    public function update(UpdateSupervisorProfileRequest $request): JsonResponse
    {
        $user = $request->user();

        $profile = $user->supervisorProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $request->validated()
        );

        return $this->success(
            new SupervisorProfileResource($profile),
            'Profil berhasil diperbarui.'
        );
    }
}
