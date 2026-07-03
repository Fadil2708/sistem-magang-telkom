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
             x-data="animatedCounter({{ $statValues[$s['key']] }})">
            <i class="{{ $s['icon'] }} welcome-stat-icon"></i>
            <span class="welcome-stat-num" x-text="displayNum">0</span>
            <span class="welcome-stat-lbl">{{ $s['label'] }}</span>
        </div>
        @endforeach
    </div>
</div>
