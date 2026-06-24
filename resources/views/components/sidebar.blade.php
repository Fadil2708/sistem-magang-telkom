@php
    $user = auth()->user();
    $role = $user->role ?? 'intern';
    $currentRoute = request()->route()?->getName() ?? '';
@endphp

<aside class="sidebar" :class="sidebarOpen ? 'open' : ''"
       x-transition:enter="anim-slide-left"
       x-transition:enter-start="-translate-x-full"
       x-transition:enter-end="translate-x-0">
    <div class="sidebar-header">
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <picture><source srcset="{{ asset('images/TLK_BIG.webp') }}" type="image/webp"><img src="{{ asset('images/TLK_BIG.png') }}" alt="Telkom Sukabumi" class="sidebar-logo-img"></picture>
        </a>
    </div>

    <nav class="sidebar-nav">
        @if($role === 'admin')
            <div class="sb-nav-section">Manajemen</div>
            <x-sidebar-item :route="'admin.dashboard'" :icon="'ti-dashboard'" :label="'Dashboard'" />

            <div class="sb-nav-section">Data Master</div>
            <x-sidebar-item :route="'admin.users*'" :icon="'ti-users'" :label="'Pengguna'" />
            <x-sidebar-item :route="'admin.vacancies.*'" :icon="'ti-briefcase'" :label="'Lowongan'" />

            <div class="sb-nav-section">Proses</div>
            <x-sidebar-item :route="'admin.applications.*'" :icon="'ti-file-description'" :label="'Lamaran'" />
            <x-sidebar-item :route="'admin.internships*'" :icon="'ti-users'" :label="'Peserta Magang'" />
            <x-sidebar-item :route="'admin.supervisors.*'" :icon="'ti-user-check'" :label="'Pembimbing'" />

            <div class="sb-nav-section">Monitoring</div>
            <x-sidebar-item :route="'admin.logbooks'" :icon="'ti-notebook'" :label="'Logbook'" />
            <x-sidebar-item :route="'admin.reports'" :icon="'ti-file-report'" :label="'Laporan'" />

            <div class="sb-nav-section">Penilaian &amp; Sertifikat</div>
            <x-sidebar-item :route="'admin.evaluations'" :icon="'ti-star'" :label="'Penilaian'" />
            <x-sidebar-item :route="'admin.certificates*'" :icon="'ti-certificate'" :label="'Sertifikat'" />

            <div class="sb-nav-section">Lainnya</div>
            <x-sidebar-item :route="'admin.invites*'" :icon="'ti-link'" :label="'Undangan'" />
            <x-sidebar-item :route="'admin.faq*'" :icon="'ti-question-mark'" :label="'FAQ'" />
            <x-sidebar-item :route="'admin.testimonials*'" :icon="'ti-message-star'" :label="'Testimoni'" />

        @elseif($role === 'supervisor')
            <x-sidebar-item :route="'supervisor.dashboard'" :icon="'ti-dashboard'" :label="'Dashboard'" />
            <x-sidebar-item :route="'supervisor.profile'" :icon="'ti-user'" :label="'Profil'" />
            <x-sidebar-item :route="'supervisor.interns.*'" :icon="'ti-users'" :label="'Peserta'" />
            <x-sidebar-item :route="'supervisor.logbooks'" :icon="'ti-notebook'" :label="'Logbook'" />
            <x-sidebar-item :route="'supervisor.reports'" :icon="'ti-file-report'" :label="'Laporan'" />
            <x-sidebar-item :route="'supervisor.evaluations.*'" :icon="'ti-star'" :label="'Penilaian'" />

        @else
            <x-sidebar-item :route="'intern.dashboard'" :icon="'ti-dashboard'" :label="'Dashboard'" />
            <x-sidebar-item :route="'intern.profile'" :icon="'ti-user'" :label="'Profil'" />

            <div class="sb-nav-section">Pendaftaran</div>
            <x-sidebar-item :route="'intern.vacancies'" :icon="'ti-briefcase'" :label="'Lowongan'" />
            <x-sidebar-item :route="'intern.applications'" :icon="'ti-file-description'" :label="'Lamaran'" />

            <div class="sb-nav-section">Kegiatan</div>
            <x-sidebar-item :route="'intern.internship'" :icon="'ti-clipboard-list'" :label="'Detail Magang'" />
            <x-sidebar-item :route="'intern.logbooks*'" :icon="'ti-notebook'" :label="'Logbook'" />
            <x-sidebar-item :route="'intern.reports'" :icon="'ti-file-report'" :label="'Laporan Akhir'" />

            <div class="sb-nav-section">Penyelesaian</div>
            <x-sidebar-item :route="'intern.evaluation'" :icon="'ti-star'" :label="'Nilai'" />
            <x-sidebar-item :route="'intern.certificate'" :icon="'ti-certificate'" :label="'Sertifikat'" />
            <x-sidebar-item :route="'intern.testimonials.create'" :icon="'ti-message-star'" :label="'Testimoni'" />
        @endif
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}"
               @click.prevent="$el.closest('form').submit()"
               class="sb-nav-item">
                <i class="ti ti-logout"></i>
                <span>Keluar</span>
            </a>
        </form>
    </div>
</aside>
