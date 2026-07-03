<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        if ($user->role === 'intern') {
            $user->load('internProfile');
            $profile = $user->internProfile;

            $required = \App\Models\InternProfile::requiredFields();

            foreach ($required as $field) {
                if (!$profile || empty($profile->{$field})) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => "Lengkapi profil Anda terlebih dahulu: {$field}",
                        ], 422);
                    }

                    return redirect()->route('profile.edit')
                        ->with('error', 'Lengkapi profil Anda terlebih dahulu sebelum mendaftar lowongan.');
                }
            }
        }

        return $next($request);
    }
}
