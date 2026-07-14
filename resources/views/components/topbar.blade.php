@props(['title' => 'Dashboard'])

@php
    $user = auth()->user();
    $prof = $user->internProfile ?? $user->supervisorProfile;
    $displayName = $prof->full_name ?? $user->email;
    $initial = strtoupper(substr($displayName, 0, 1));
    $hasPhoto = $user->isIntern() && $user->internProfile?->photo_url;
@endphp

<div class="topbar" x-data="{ dropOpen: false }">
    <div class="topbar-left">
        <button @click="sidebarOpen = !sidebarOpen"
                class="hamburger-btn">
            <i class="ti ti-menu-2"></i>
        </button>
        <div class="topbar-title">{{ $title }}</div>
    </div>

    <div class="topbar-right">
        <livewire:notification-bell key="notif-bell-{{ auth()->id() }}" />
        <div @click.outside="dropOpen = false" class="topbar-dropdown-wrap">
            <button @click="dropOpen = !dropOpen"
                    class="topbar-user-btn"
                    :class="dropOpen ? 'topbar-user-btn-open' : ''">
                @if($hasPhoto)
                    <img src="{{ route('profile.photo') }}" alt=""
                         class="topbar-avatar-img">
                @else
                    <span class="topbar-avatar">{{ $initial }}</span>
                @endif
                <span class="topbar-name">{{ $displayName }}</span>
                <i class="ti ti-chevron-down topbar-chevron"
                   :class="{ 'rotate-180': dropOpen }"></i>
            </button>

            <div x-show="dropOpen" x-cloak
                 x-transition:enter="anim-slide-down"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="anim-fade-in"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="topbar-dropdown">
                <div class="topbar-dropdown-header">
                    <p class="topbar-dropdown-name">{{ $displayName }}</p>
                    <p class="topbar-dropdown-email">{{ $user->email }}</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="topbar-dropdown-item">
                    <i class="ti ti-user"></i>
                    Profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                       @click.prevent="$el.closest('form').submit()"
                       class="topbar-dropdown-item topbar-dropdown-item-danger">
                        <i class="ti ti-logout"></i>
                        Keluar
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>
