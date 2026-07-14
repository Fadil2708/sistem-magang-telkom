@extends('layouts.app')
@section('title', 'Notifikasi')
@php $pageTitle = 'Notifikasi'; @endphp

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Notifikasi</h2>
        <p class="page-sub">Semua notifikasi akun Anda</p>
    </div>
    @if(auth()->user()->unreadNotifications->isNotEmpty())
        <form method="POST" action="{{ route('notifications.read-all') }}"
              x-data="{ loading: false }" @submit="loading = true">
            @csrf
            <button type="submit" class="btn-secondary btn-sm"
                    :disabled="loading" :class="loading && 'opacity-60 cursor-wait'">
                <template x-if="!loading"><span><i class="ti ti-check"></i> Tandai semua dibaca</span></template>
                <template x-if="loading"><span><i class="ti ti-loader" style="animation:spin 1s linear infinite"></i> Memproses...</span></template>
            </button>
        </form>
    @endif
</div>

<div class="panel" style="padding: 0; overflow: hidden;">
    @forelse($notifications as $notif)
        @php $data = $notif['data']; @endphp
        <div class="notif-page-item {{ $notif['read_at'] ? '' : 'notif-page-item-unread' }}">
            @if(!$notif['read_at'])
                <a href="{{ route('notifications.read', $notif['id']) }}?redirect={{ urlencode($data['url'] ?? route('notifications')) }}" class="notif-page-link">
            @else
                <a href="{{ $data['url'] ?? '#' }}" class="notif-page-link">
            @endif
                <div class="notif-page-icon">
                    <i class="ti ti-{{ match(explode('.', $data['type'] ?? '')[0]) {
                        'application' => 'file-description',
                        'logbook' => 'notebook',
                        'report' => 'file-report',
                        'certificate' => 'certificate',
                        default => 'bell',
                    } }}"></i>
                </div>
                <div class="notif-page-content">
                    <div class="notif-page-msg">{{ $data['title'] ?? 'Notifikasi' }}</div>
                    <div class="notif-page-body">{{ $data['body'] ?? '' }}</div>
                    <div class="notif-page-time">{{ $notif['created_at'] ? \Carbon\Carbon::parse($notif['created_at'])->diffForHumans() : '' }}</div>
                </div>
                @if(!$notif['read_at'])
                    <span class="notif-page-dot"></span>
                @endif
            </a>
        </div>
    @empty
        <div class="notif-page-empty">
            <i class="ti ti-bell-off"></i>
            <span>Tidak ada notifikasi</span>
        </div>
    @endforelse
</div>

<div class="pagination-wrap" style="margin-top: 16px;">
    {{ $notifications->links() }}
</div>
@endsection

@push('styles')
<style>
.notif-page-item {
    border-bottom: 1px solid #F5F4F2;
    transition: background 0.1s;
}
.notif-page-item:last-child { border-bottom: none; }
.notif-page-item-unread { background: #F9EAE8; }
.notif-page-item:hover { background: #F5F4F2; }
.notif-page-link {
    display: flex; gap: 12px; padding: 14px 16px;
    text-decoration: none; align-items: flex-start;
}
.notif-page-icon {
    width: 36px; height: 36px; border-radius: 50%;
    background: #F5F4F2; color: #5C5A55;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.notif-page-icon i { font-size: 17px; }
.notif-page-content { flex: 1; min-width: 0; }
.notif-page-msg {
    font-size: 13px; font-weight: 500; color: #312F2D;
}
.notif-page-body {
    font-size: 12px; color: #A8A5A0; margin-top: 2px;
}
.notif-page-time {
    font-size: 11px; color: #C0392B; margin-top: 4px;
}
.notif-page-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: #DC2626; flex-shrink: 0; margin-top: 6px;
}
.notif-page-empty {
    padding: 48px 16px; text-align: center;
    color: #A8A5A0; font-size: 13px;
}
.notif-page-empty i {
    display: block; font-size: 32px; margin-bottom: 8px;
}
@media (max-width: 768px) {
    .notif-page-link { padding: 12px 14px; }
    .notif-page-icon { width: 32px; height: 32px; }
    .notif-page-icon i { font-size: 15px; }
}
</style>
@endpush
