@props(['id' => 'modal', 'title' => 'Modal', 'maxWidth' => 'md'])

@php
    $widthClass = match ($maxWidth) {
        'sm' => 'modal-card-sm',
        'md' => 'modal-card-md',
        'lg' => 'modal-card-lg',
        'xl' => 'modal-card-xl',
        default => 'modal-card-md',
    };
@endphp

<div x-data="{ open: false }" id="{{ $id }}-wrapper" {{ $attributes->whereStartsWith('wire:key') }}>
    <div @click="open = true">{{ $trigger ?? '' }}</div>

    <div x-show="open" x-transition.opacity class="modal-wrap" @click.self="open = false">
        <div class="modal-backdrop"></div>
        <div class="modal-center" @click="open = false">
            <div x-show="open" x-transition class="modal-card {{ $widthClass }}" @click.stop>
                <div class="modal-header">
                    <div class="modal-title">{{ $title }}</div>
                    <button @click="open = false" class="modal-close" aria-label="Tutup">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <div class="modal-body">{{ $slot }}</div>
            </div>
        </div>
    </div>
</div>
