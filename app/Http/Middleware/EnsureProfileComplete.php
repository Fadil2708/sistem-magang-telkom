<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->role === 'intern') {
            $request->user()->load('internProfile');
            $profile = $request->user()->internProfile;

            $required = ['full_name', 'institution_name', 'major', 'student_id', 'cv_url'];

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
