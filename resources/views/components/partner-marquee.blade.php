@props([
    'partners' => [
        ['name' => 'UNPAK', 'logo' => ''],
        ['name' => 'UNSIKA', 'logo' => ''],
        ['name' => 'SMKN 1', 'logo' => ''],
        ['name' => 'SMKN 2', 'logo' => ''],
        ['name' => 'SMKN 3', 'logo' => ''],
        ['name' => 'POLTEK', 'logo' => ''],
    ],
    'speed' => 25,
])

<div class="welcome-partners" data-reveal>
    <p class="partners-label">Dipercaya oleh Institusi Mitra</p>
    <div class="partners-marquee">
        <div class="partners-track"
             x-data="partnerMarquee({{ $speed }})">
            @foreach ($partners as $p)
                <div class="partner-logo">
                    @if (!empty($p['logo']))
                        <img src="{{ $p['logo'] }}" alt="{{ $p['name'] }}" class="inline-block h-6 w-auto object-contain">
                    @else
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-light text-brand font-bold text-xs leading-none flex-shrink-0">{{ substr($p['name'], 0, 2) }}</span>
                    @endif
                    <span class="ms-2 text-sm font-medium">{{ $p['name'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>


