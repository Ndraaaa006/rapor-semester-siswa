@extends('layouts.app')

@section('title', 'Dashboard Siswa / Wali')

@section('content')
    @php
        $user = auth()->user();
        $grades = $grades ?? collect();
        $isPublished = (bool) ($activeYear?->is_published ?? false);
        $avgScore = ($isPublished && $grades->isNotEmpty())
            ? number_format((float) $grades->avg('nilai_akhir'), 2)
            : '-';
    @endphp

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Dashboard Siswa / Wali</h1>
            <p class="text-muted small mb-0">
                Pantau status publikasi rapor semester, rekap nilai mata pelajaran, dan cetak rapor resmi.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('siswa.dashboard', ['section' => 'rapor-semester']) }}" class="btn rd-btn-emerald px-3 py-2 small">
                <i class="bi bi-file-earmark-text me-1"></i> Lihat &amp; Cetak Rapor
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stat-card
                label="Status Rapor"
                :value="$isPublished ? 'Terbuka' : 'Dikunci'"
                :meta="($activeYear?->name ?? 'Periode') . ' · ' . ($activeYear?->semester ?? '-')"
                :href="route('siswa.dashboard', ['section' => 'rapor-semester'])"
                :featured="true"
            />
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stat-card
                label="Rombongan Belajar"
                :value="$user?->classroom?->name ?? '-'"
                :meta="'Tingkat ' . ($user?->classroom?->level ?? '-')"
            />
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stat-card
                label="Jumlah Mapel"
                :value="$isPublished ? $grades->count() : '-'"
                :meta="$isPublished ? 'Nilai semester tersedia' : 'Menunggu publikasi admin'"
            />
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stat-card
                label="Rata-Rata Nilai"
                :value="$avgScore"
                meta="Akumulasi nilai akhir"
                :href="route('siswa.dashboard', ['section' => 'rapor-semester'])"
            />
        </div>
    </div>

    <div class="rd-card p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h6 fw-bold mb-0">Informasi Rapor Semester</h2>
            <span class="badge rounded-pill {{ $isPublished ? 'text-bg-success' : 'text-bg-warning text-dark' }} px-3 py-2">
                {{ $isPublished ? 'Siap Diakses' : 'Belum Dipublikasikan' }}
            </span>
        </div>

        @if ($isPublished)
            <p class="text-muted small mb-3">
                Rapor untuk periode <strong>{{ $activeYear?->name }} ({{ $activeYear?->semester }})</strong> sudah dibuka. Silakan buka menu <strong>Rapor Semester</strong> untuk melihat rincian nilai dan mencetak rapor.
            </p>
            <a href="{{ route('siswa.dashboard', ['section' => 'rapor-semester']) }}" class="btn btn-sm rd-btn-emerald px-3">
                Buka Rapor Semester <i class="bi bi-arrow-right ms-1"></i>
            </a>
        @else
            <div class="alert alert-warning border-0 rounded-3 mb-0 small">
                <i class="bi bi-lock-fill me-1"></i>
                Akses rapor semester saat ini masih dikunci oleh Admin Akademik. Nilai belum dapat ditampilkan sampai periode dipublikasikan.
            </div>
        @endif
    </div>
@endsection
