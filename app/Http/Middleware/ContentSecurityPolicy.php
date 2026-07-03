<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        $nonce = base64_encode(random_bytes(16));

        View::share('cspNonce', $nonce);
        Vite::useScriptTagAttributes(['nonce' => $nonce]);
        Livewire::useScriptTagAttributes(['nonce' => $nonce]);

        $response = $next($request);

        $policy = collect(config('csp.directives'))
            ->map(fn($sources, $directive) => $directive . ' ' . implode(' ', str_replace('{nonce}', $nonce, $sources)))
            ->implode('; ');

        $response->headers->set('Content-Security-Policy', $policy);

        return $response;
    }
}
