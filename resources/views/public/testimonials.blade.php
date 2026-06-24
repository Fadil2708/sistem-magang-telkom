@extends('layouts.public')
@section('title', 'Testimoni')

@section('content')
<main class="public-page">
    <div class="page-header-center">
        <h2 class="page-title page-title-lg">Testimoni</h2>
        <p class="page-sub">Apa kata mereka setelah mengikuti program magang/PKL di Telkom Sukabumi</p>
    </div>

    @if(($testimonials ?? collect())->count() > 0)
        <div class="testi-grid">
            @foreach($testimonials as $testimonial)
            @php
                $tName = $testimonial->intern->internProfile->full_name ?? 'Anonymous';
            @endphp
            <div class="panel testi-card">
                <div class="testi-author">
                    <x-avatar :name="$tName" :size="36" :font-size="14" />
                    <div>
                        <div class="testi-name">{{ $tName }}</div>
                        <div class="testi-inst">{{ $testimonial->intern->internProfile->institution_name ?? '' }}</div>
                    </div>
                </div>
                <p class="testi-text">{{ $testimonial->message ?? $testimonial->content ?? '' }}</p>
                <p class="testi-date">{{ $testimonial->created_at?->format('d F Y') }}</p>
            </div>
            @endforeach
        </div>

        @if(method_exists($testimonials, 'links'))
        <div class="pagination-wrap pagination-wrap-center">
            {{ $testimonials->links('components.pagination', ['paginator' => $testimonials]) }}
        </div>
        @endif
    @else
    <x-empty-state icon="ti-message-star" message="Belum ada testimonial yang tersedia." />
    @endif
</main>
@endsection
