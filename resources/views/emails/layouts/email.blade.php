<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { margin: 0; padding: 0; background-color: #f3f4f6; font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif; -webkit-font-smoothing: antialiased; }
        .wrapper { max-width: 560px; margin: 0 auto; padding: 24px 12px; }
        .header { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); padding: 36px 24px; text-align: center; border-radius: 10px 10px 0 0; }
        .header-logo { width: 48px; height: 48px; background: #000; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .header h1 { color: #ffffff; margin: 0; font-size: 20px; font-weight: 700; letter-spacing: -0.3px; }
        .header p { color: #fca5a5; margin: 6px 0 0; font-size: 13px; }
        .content-body { background: #ffffff; padding: 32px 28px; }
        .content-body h2 { color: #111827; font-size: 18px; font-weight: 700; margin: 0 0 16px; }
        .content-body p { color: #374151; font-size: 14px; line-height: 1.7; margin: 0 0 12px; }
        .content-body .info-box { background: #fef2f2; border-left: 4px solid #dc2626; padding: 14px 16px; margin: 18px 0; border-radius: 6px; }
        .content-body .info-box p { margin: 4px 0; font-size: 13px; color: #4b5563; }
        .content-body .info-box strong { color: #111827; }
        .content-body .info-green { background: #f0fdf4; border-left-color: #16a34a; }
        .content-body .info-blue { background: #F9EAE8; border-left-color: #C0392B; }
        .content-body .info-amber { background: #fffbeb; border-left-color: #d97706; }
        .btn-wrap { text-align: center; margin: 24px 0; }
        .btn { display: inline-block; padding: 12px 28px; background: #dc2626; color: #ffffff; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600; box-shadow: 0 2px 6px rgba(220,38,38,0.25); transition: background 0.2s, box-shadow 0.2s; }
        .btn:hover { background: #b91c1c; box-shadow: 0 4px 12px rgba(220,38,38,0.35); }
        .btn-green { background: #16a34a; box-shadow: 0 2px 6px rgba(22,163,74,0.25); }
        .btn-green:hover { background: #15803d; box-shadow: 0 4px 12px rgba(22,163,74,0.35); }
        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 20px 0; }
        .footer { background: #f9fafb; padding: 20px 28px; text-align: center; border-radius: 0 0 10px 10px; border-top: 1px solid #e5e7eb; }
        .footer p { color: #6b7280; font-size: 12px; margin: 3px 0; line-height: 1.5; }
        .footer .brand-link { color: #dc2626; text-decoration: none; font-weight: 500; }
        @media only screen and (max-width: 480px) {
            .wrapper { padding: 12px 6px; }
            .content-body { padding: 24px 16px; }
            .header { padding: 24px 16px; }
            .header h1 { font-size: 17px; }
            .btn { display: block; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="header-logo"><img src="{{ asset('images/TLK.webp') }}" alt="Telkom Indonesia" style="max-width:32px; max-height:32px; display:block;"></div>
            <h1>Sistem Magang &amp; PKL Telkom Sukabumi</h1>
            <p>Telkom Sukabumi</p>
        </div>
        <div class="content-body">
            @yield('content')
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} <a href="#" class="brand-link">Telkom Sukabumi</a>. All rights reserved.</p>
            <p>Email ini dikirim secara otomatis dari sistem <strong>Sistem Magang & PKL</strong>, harap tidak membalas.</p>
        </div>
    </div>
</body>
</html>
