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

<div class="welcome-partners dark:bg-neutral-950" data-reveal>
    <p class="partners-label dark:text-neutral-600">Dipercaya oleh Institusi Mitra</p>
    <div class="partners-marquee">
        <div class="partners-track"
             x-data="partnerMarquee({{ $speed }})">
            @foreach ($partners as $p)
                <div class="partner-logo dark:bg-neutral-800 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-brand-DEFAULT">
                    @if (!empty($p['logo']))
                        <img src="{{ $p['logo'] }}" alt="{{ $p['name'] }}" class="inline-block h-6 w-auto object-contain dark:brightness-90">
                    @else
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-light dark:bg-neutral-700 text-brand dark:text-neutral-300 font-bold text-xs leading-none flex-shrink-0">{{ substr($p['name'], 0, 2) }}</span>
                    @endif
                    <span class="ms-2 text-sm font-medium">{{ $p['name'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script nonce="{{ $cspNonce }}">
document.addEventListener('alpine:init', () => {
    Alpine.data('partnerMarquee', (speed) => ({
        init() {
            const track = this.$el;
            const container = track.parentElement;
            const origItems = Array.from(track.children);
            if (origItems.length === 0) return;
            const measure = () => {
                const last = origItems[origItems.length - 1];
                return last.offsetLeft + last.offsetWidth;
            };

            let anim = null;
            let origW = measure();

            const ensure = () => {
                if (origW === 0) return;
                const cw = container.offsetWidth;
                const sets = Math.ceil(track.children.length / origItems.length);
                const need = Math.max(2, Math.ceil((cw * 2) / origW));
                for (let i = sets; i < need; i++)
                    origItems.forEach(el => track.appendChild(el.cloneNode(true)));
            };

            const start = () => {
                if (anim) anim.cancel();
                origW = measure();
                if (origW === 0) return;
                anim = track.animate([
                    { transform: 'translateX(0)' },
                    { transform: `translateX(-${origW}px)` },
                ], { duration: speed * 1000, iterations: Infinity });
            };

            ensure();
            start();

            let t;
            addEventListener('resize', () => {
                clearTimeout(t);
                t = setTimeout(() => { measure(); ensure(); start(); }, 150);
            });

            this._c = () => { if (anim) anim.cancel(); };
        },
        destroy() { if (this._c) this._c(); }
    }));
});
</script>
@endpush
