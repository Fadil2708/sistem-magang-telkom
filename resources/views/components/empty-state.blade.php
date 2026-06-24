@props(['icon' => 'ti-inbox', 'message' => 'Tidak ada data'])

<div class="empty-state-wrap">
    <div class="empty-state-icon">
        <i class="ti {{ $icon }}"></i>
    </div>
    <div class="empty-state-msg">{{ $message }}</div>
    {{ $slot }}
</div>
