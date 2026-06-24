<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401)
                : redirect()->route('login');
        }

        if (!in_array($request->user()->role, $roles)) {
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => 'Forbidden.'], 403)
                : redirect()->route($request->user()->role . '.dashboard');
        }

        return $next($request);
    }
}
