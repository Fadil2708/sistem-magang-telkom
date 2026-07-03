<div class="section-divider-wave"></div>
<div class="welcome-stats" data-reveal>
    <div class="welcome-stats-grid">
        @php
            $statValues = [
                'interns' => $stats['interns'],
                'supervisors' => $stats['supervisors'],
                'completed' => $stats['completed'],
            ];
        @endphp
        @foreach ([
            ['key' => 'interns', 'icon' => 'ti ti-users', 'label' => 'Peserta Magang'],
            ['key' => 'supervisors', 'icon' => 'ti ti-user-star', 'label' => 'Pembimbing'],
            ['key' => 'completed', 'icon' => 'ti ti-certificate', 'label' => 'Lulusan'],
        ] as $s)
        <div class="welcome-stat"
             x-data="{ num: 0, target: {{ $statValues[$s['key']] }}, counting: false }"
             x-init="
                 const obs = new IntersectionObserver(([entry]) => {
                     if (entry.isIntersecting && !counting) {
                         counting = true;
                         obs.unobserve($el);
                         const step = Math.ceil(target / 30);
                         let t = setInterval(() => {
                             num = Math.min(num + step, target);
                             if (num >= target) clearInterval(t);
                         }, 30);
                     }
                 }, { threshold: 0.3 });
                 obs.observe($el);
             ">
            <i class="{{ $s['icon'] }} welcome-stat-icon"></i>
            <span class="welcome-stat-num" x-text="num.toLocaleString() + '+'">0</span>
            <span class="welcome-stat-lbl">{{ $s['label'] }}</span>
        </div>
        @endforeach
    </div>
</div>
