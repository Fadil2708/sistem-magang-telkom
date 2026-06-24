@props(['icon' => 'ti-chart-bar', 'value' => '0', 'label' => '', 'color' => ''])

@php
    $iconClass = match ($color) {
        'red'   => 'icon-wrap-brand',
        'blue'  => 'icon-wrap-blue',
        'green' => 'icon-wrap-green',
        'amber' => 'icon-wrap-amber',
        default => 'icon-wrap-brand',
    };
@endphp

<div class="stat-card">
    <div class="stat-card-icon {{ $iconClass }}">
        <i class="ti {{ $icon }}"></i>
    </div>
    <div class="stat-card-body">
        <div class="stat-card-value">{{ $value }}</div>
        <div class="stat-card-label">{{ $label }}</div>
    </div>
</div>
