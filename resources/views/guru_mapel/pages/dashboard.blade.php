@extends('layouts.app')

@section('title', 'Dashboard Guru Mapel')

@section('content')
    @php
        $assignments = $assignments ?? collect();
        $grades = $grades ?? collect();
        $finalizedCount = $grades->where('is_finalized', true)->count();
        $draftCount = $grades->where('is_finalized', false)->count();
        $avgScore = $grades->isNotEmpty() ? number_format((float) $grades->avg('nilai_akhir'), 2) : '0.00';
    @endphp

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Dashboard Guru Mapel</h1>
            <p class="text-muted small mb-0">Kelola penugasan mengajar, input komponen nilai siswa, dan finalisasi nilai akhir.</p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('guru.dashboard', ['section' => 'input-nilai']) }}" class="btn rd-btn-emerald px-3 py-2 small">
                <i class="bi bi-pencil-square me-1"></i> Input &amp; Finalisasi Nilai
            </a>
            <a href="{{ route('guru.dashboard', ['section' => 'rekap-penugasan']) }}" class="btn btn-light border rounded-pill px-3 py-2 small fw-semibold">
                Rekap Penugasan
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stat-card
                label="Penugasan Aktif"
                :value="$assignments->count()"
                :meta="($activeYear?->name ?? 'Periode') . ' · ' . ($activeYear?->semester ?? '-')"
                :href="route('guru.dashboard', ['section' => 'rekap-penugasan'])"
                :featured="true"
            />
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stat-card
                label="Rata-Rata Nilai"
                :value="$avgScore"
                :meta="$grades->count() . ' data nilai tercatat'"
                :href="route('guru.dashboard', ['section' => 'input-nilai'])"
            />
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stat-card
                label="Nilai Difinalisasi"
                :value="$finalizedCount"
                meta="Terkunci untuk rapor"
                :href="route('guru.dashboard', ['section' => 'input-nilai'])"
            />
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stat-card
                label="Masih Draf"
                :value="$draftCount"
                meta="Dapat diperbarui guru"
                :href="route('guru.dashboard', ['section' => 'input-nilai'])"
            />
        </div>
    </div>

    <div class="rd-card p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h6 fw-bold mb-0">Daftar Kelas &amp; Mata Pelajaran Diampu</h2>
            <a href="{{ route('guru.dashboard', ['section' => 'input-nilai']) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                Buka Input Nilai
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-uppercase text-muted">
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Periode</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assignments as $item)
                        <tr>
                            <td class="fw-bold text-dark">{{ $item->subject?->name ?? 'Mapel' }}</td>
                            <td>
                                <span class="badge rounded-pill text-bg-success-subtle text-success border border-success-subtle px-3 py-2">
                                    {{ $item->classroom?->name ?? 'Kelas' }}
                                </span>
                            </td>
                            <td class="text-muted small">{{ $item->academicYear?->name ?? '-' }} ({{ $item->academicYear?->semester ?? '-' }})</td>
                            <td class="text-end">
                                <a href="{{ route('guru.dashboard', ['section' => 'input-nilai', 'assignment_id' => $item->id]) }}" class="btn btn-sm rd-btn-emerald px-3">
                                    Input Nilai
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted small py-4">
                                Belum ada penugasan mengajar untuk akun ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
