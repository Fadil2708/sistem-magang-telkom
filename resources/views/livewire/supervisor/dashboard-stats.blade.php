<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <x-stat-card icon="ti-users" value="{{ $totalInterns }}" label="Peserta Bimbingan" color="red" />
    <x-stat-card icon="ti-notebook" value="{{ $pendingLogbooks }}" label="Logbook Perlu Review" color="amber" />
    <x-stat-card icon="ti-file-description" value="{{ $pendingReports }}" label="Laporan Akhir Menunggu" color="blue" />
    <x-stat-card icon="ti-circle-check" value="{{ $approvedLogbooks }}" label="Logbook Disetujui" color="green" />
</div>
