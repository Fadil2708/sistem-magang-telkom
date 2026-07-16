<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy
    |--------------------------------------------------------------------------
    |
    | Define the directives for the Content-Security-Policy header.
    | The {nonce} placeholder will be replaced with a random nonce per request.
    |
    */

    'directives' => [
        'default-src' => ["'self'"],
        'script-src' => [
            "'self'",
            "'unsafe-inline'",
            // Required by Alpine.js CDN build (new Function()).
            // Remove only after CSP build migration (Sprint 6).
            "'unsafe-eval'",
            'https://cdn.jsdelivr.net',
            'https://unpkg.com',
        ],
        'style-src' => [
            "'self'",
            "'unsafe-inline'",
            'https://fonts.googleapis.com',
            'https://cdn.jsdelivr.net',
        ],
        'font-src' => [
            "'self'",
            'https://fonts.gstatic.com',
            'https://cdn.jsdelivr.net',
        ],
        'img-src' => [
            "'self'",
            'data:',
        ],
        'connect-src' => [
            "'self'",
            'https://cdn.jsdelivr.net',
            'https://unpkg.com',
        ],
        'form-action' => ["'self'"],
        'base-uri' => ["'self'"],
        'frame-ancestors' => ["'none'"],
    ],
];
