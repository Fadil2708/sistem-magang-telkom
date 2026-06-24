<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Telkom Sukabumi</title>
    <meta name="description" content="Sistem Informasi Pengelolaan Magang & PKL Telkom Sukabumi — pendaftaran, monitoring, dan evaluasi program magang secara digital.">

    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('images/TLK.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/TLK.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" media="print" id="font-css">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap">
    </noscript>
    <script nonce="{{ $cspNonce }}">document.getElementById('font-css').media='all'</script>

    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.33.0/dist/tabler-icons.min.css">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body>
    <div class="auth-split" x-data="{ role: '@yield('auth-init', 'intern')' }">
        <div class="auth-brand">
            <div class="auth-brand-content">
                <picture><source srcset="{{ asset('images/TLK.webp') }}" type="image/webp"><img src="{{ asset('images/TLK.png') }}" alt="Telkom" class="brand-icon-img" width="56" height="56"></picture>
                <h1>Telkom Sukabumi</h1>
                <p class="brand-sub" x-show="role === 'intern'">Mulai Perjalanan Magang & PKL Anda</p>
                <p class="brand-sub" x-show="role === 'supervisor'" x-cloak>Bimbing Generasi Baru Indonesia</p>

                <div class="auth-testimonial" x-show="role === 'intern'">
                    <p>"Magang di Telkom adalah pengalaman yang luar biasa. Saya belajar banyak tentang dunia kerja profesional dan mendapatkan bimbingan dari mentor yang berpengalaman."</p>
                    <div class="attribution">
                        <div class="av">A</div>
                        <div>
                            <div class="name">Ahmad Fauzi</div>
                            <div class="role">Alumni Magang 2025</div>
                        </div>
                    </div>
                </div>

                <div class="auth-testimonial" x-show="role === 'supervisor'" x-cloak>
                    <p>"Sebagai pembimbing, saya bangga bisa membantu generasi muda mengembangkan potensi mereka di dunia profesional. Melihat mereka tumbuh adalah kepuasan tersendiri."</p>
                    <div class="attribution">
                        <div class="av">R</div>
                        <div>
                            <div class="name">Rina Wijaya</div>
                            <div class="role">Pembimbing Magang Telkom</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="auth-form-wrap">
            <div class="auth-form-card">
                @yield('content')
                {{ $slot ?? '' }}
            </div>
        </div>
    </div>
</body>
</html>
