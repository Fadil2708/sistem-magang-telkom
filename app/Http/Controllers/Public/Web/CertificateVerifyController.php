<?php

namespace App\Http\Controllers\Public\Web;

use App\Http\Controllers\Controller;
use App\Models\Certificate;

class CertificateVerifyController extends Controller
{
    public function show(string $token)
    {
        $certificate = Certificate::where('qr_code_token', $token)
            ->with([
                'intern.internProfile',
                'internship.vacancy',
                'issuedBy.supervisorProfile',
            ])
            ->firstOrFail();

        return view('public.verify', compact('certificate'));
    }
}
