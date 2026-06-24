<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function serve(string $path)
    {
        $disk = Storage::disk('private');
        $diskRoot = realpath($disk->path(''));
        $fullPath = realpath($disk->path($path));

        if ($fullPath === false || $diskRoot === false) {
            abort(404);
        }

        if (!str_starts_with($fullPath, $diskRoot)) {
            abort(403, 'Invalid file path.');
        }

        $user = auth()->user();

        if ($user->role === 'admin') {
            return response()->file($fullPath);
        }

        $segments = explode('/', $path);

        if (count($segments) < 2) {
            abort(403, 'Invalid file path.');
        }

        $type = $segments[0];
        $ownerId = $segments[1];

        if ($type === 'reports') {
            $internship = Internship::with('intern')->find($ownerId);
            if (!$internship) {
                abort(404);
            }
            $isSupervisor = $internship->supervisor_id !== null && $internship->supervisor_id === $user->id;
            $isIntern = $internship->intern_id === $user->id;
            if (!$isSupervisor && !$isIntern) {
                abort(403, 'You do not have access to this file.');
            }
        } elseif ($user->id !== $ownerId) {
            abort(403, 'You do not have access to this file.');
        }

        return response()->file($fullPath);
    }
}
