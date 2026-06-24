@extends('layouts.app')
@section('title', 'Dashboard')
@php $pageTitle = 'Dashboard'; @endphp

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Dashboard</h2>
        <p class="page-sub">Selamat datang, {{ auth()->user()->name ?? '—' }}</p>
    </div>
</div>

<div class="flex items-center justify-center p-10 text-center">
    <div class="panel max-w-[420px] p-10">
        <i class="ti ti-home text-[40px] text-[#D0CEC9] mb-4 block"></i>
        <h2 class="text-lg font-bold text-[#1E1C1A] mb-2">Selamat Datang</h2>
        <p class="text-[13px] text-[#5C5A55] leading-relaxed">
            Kamu login sebagai <strong>{{ auth()->user()->role === 'admin' ? 'Admin' : (auth()->user()->role === 'supervisor' ? 'Pembimbing' : 'Peserta Magang') }}</strong>.
            Gunakan menu sidebar untuk mengakses fitur yang tersedia.
        </p>
    </div>
</div>
@endsection
