@props(['title' => 'Dashboard'])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $title) — Telkom Sukabumi</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap" rel="stylesheet">

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
        @if(auth()->check() && auth()->user()->isIntern() && !auth()->user()->internProfile?->isComplete())
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

@vite(['resources/js/app.js'])
@stack('scripts')
@livewireScripts

</body>
</html>
