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

        $response->headers->set('Content-Security-Policy',
            "default-src 'self';" .
            "script-src 'self' 'nonce-{$nonce}' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com;" .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net;" .
            "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net;" .
            "img-src 'self' data:;" .
            "connect-src 'self';" .
            "form-action 'self';" .
            "base-uri 'self';" .
            "frame-ancestors 'none';"
        );

        return $response;
    }
}
