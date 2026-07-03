<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProfileController extends Controller
{
    use ApiResponse;

    public function show(Request $request): JsonResponse
    {
        $user = $request->user()->load(['internProfile', 'supervisorProfile']);
        return $this->success(new UserResource($user), 'Profil Anda');
    }


    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function photo(Request $request): StreamedResponse|\Illuminate\Http\Response
    {
        $user = $request->user();

        if ($user->isIntern() && $user->internProfile?->photo_url) {
            $disk = Storage::disk(config('filesystems.private_disk'));
            $path = $user->internProfile->photo_url;

            if ($disk->exists($path)) {
                return $disk->response($path);
            }
        }

        $initial = strtoupper(substr($user->displayName() ?? $user->email, 0, 1));
        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
  <rect width="64" height="64" rx="32" fill="#C0392B"/>
  <text x="32" y="40" text-anchor="middle" fill="white"
        font-size="28" font-family="sans-serif" font-weight="bold">{$initial}</text>
</svg>
SVG;

        return response($svg, 200, ['Content-Type' => 'image/svg+xml']);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
