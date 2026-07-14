@props(['title' => 'Dashboard'])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $title) — Telkom Sukabumi</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/webp" sizes="512x512" href="{{ asset('images/TLK.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/TLK.webp') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.33.0/dist/tabler-icons.min.css">

    @vite(['resources/css/app.css'])
    @stack('styles')
    @livewireStyles
</head>
<body>

<style>[x-cloak] { display: none !important; }</style>

<div class="dash-wrap" x-data="{ sidebarOpen: false }">
    {{-- Sidebar backdrop --}}
    <div class="sidebar-backdrop" :class="sidebarOpen ? 'show' : ''" @click="sidebarOpen = false"></div>

    {{-- Sidebar --}}
    @include('components.sidebar')

    <div class="main-content">
        @if($showProfileStrip)
            @include('components.profile-strip')
        @endif

        @include('components.topbar', ['title' => $pageTitle ?? ($title ?? 'Dashboard')])

        <div class="dash-body">
            @yield('content')
            {{ $slot ?? '' }}
        </div>
    </div>
</div>

@include('components.toast')
@include('components.confirm-modal')

@vite(['resources/js/auth.js'])
@stack('scripts')
@livewireScripts

</body>
</html>
