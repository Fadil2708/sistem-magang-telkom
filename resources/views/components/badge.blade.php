@props(['status' => 'draft'])

@php
    $map = [
        'submitted'           => 'submitted',
        'under_review'        => 'review',
        'interview_scheduled' => 'pending',
        'accepted'            => 'accepted',
        'rejected'            => 'rejected',
        'active'              => 'active',
        'completed'           => 'approved',
        'terminated'          => 'rejected',
        'draft'               => 'draft',
        'open'                => 'accepted',
        'closed'              => 'draft',
        'approved'            => 'approved',
        'pending'             => 'pending',
        'revision_requested'  => 'pending',
    ];
    $labels = [
        'submitted'           => 'Terkirim',
        'under_review'        => 'Direview',
        'interview_scheduled' => 'Interview',
        'accepted'            => 'Diterima',
        'rejected'            => 'Ditolak',
        'active'              => 'Aktif',
        'completed'           => 'Selesai',
        'terminated'          => 'Terminasi',
        'draft'               => 'Draft',
        'open'                => 'Open',
        'closed'              => 'Closed',
        'approved'            => 'Disetujui',
        'pending'             => 'Menunggu',
        'revision_requested'  => 'Revisi',
    ];
    $class = $map[$status] ?? 'draft';
    $label = $labels[$status] ?? ucfirst($status);
@endphp

<span class="badge {{ $class }}">{{ $label }}</span>
