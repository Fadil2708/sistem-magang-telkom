<div wire:poll.30s="refresh" x-data="{ notifOpen: false }" class="topbar-notif-wrap">
    <button @click="notifOpen = !notifOpen; if(notifOpen) $wire.refresh()"
            class="topbar-notif-btn"
            :class="notifOpen ? 'topbar-notif-btn-open' : ''">
        <i class="ti ti-bell"></i>
        @if($unreadCount > 0)
            <span class="topbar-notif-badge">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
        @endif
    </button>

    <div x-show="notifOpen" x-cloak
         @click.outside="notifOpen = false"
         x-transition:enter="anim-slide-down"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="anim-fade-in"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="topbar-notif-dropdown">
        <div class="topbar-notif-header">
            <span class="topbar-notif-title">Notifikasi</span>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="topbar-notif-mark-read">Tandai semua dibaca</button>
            @endif
        </div>

        <div class="topbar-notif-list">
            @forelse($recentNotifications as $notif)
                @php $data = $notif['data']; @endphp
                <a href="{{ $data['url'] ?? '#' }}"
                   wire:click="markAsRead('{{ $notif['id'] }}')"
                   class="topbar-notif-item">
                    <div class="topbar-notif-icon">
                        <i class="ti ti-{{ match(explode('.', $data['type'] ?? '')[0]) {
                            'application' => 'file-description',
                            'logbook' => 'notebook',
                            'report' => 'file-report',
                            'certificate' => 'certificate',
                            default => 'bell',
                        } }}"></i>
                    </div>
                    <div class="topbar-notif-content">
                        <div class="topbar-notif-msg">{{ $data['title'] ?? 'Notifikasi' }}</div>
                        <div class="topbar-notif-body">{{ $data['body'] ?? '' }}</div>
                    </div>
                </a>
            @empty
                <div class="topbar-notif-empty">
                    <i class="ti ti-bell-off"></i>
                    <span>Tidak ada notifikasi</span>
                </div>
            @endforelse
        </div>

        @if(auth()->user()->unreadNotifications()->count() > 5)
            <a href="{{ route('notifications') }}" class="topbar-notif-footer">
                Lihat semua notifikasi
            </a>
        @endif
    </div>
</div>
