@extends('layouts.app')

@section('title', 'Rapor Semester Siswa')

@section('content')
    @php
        $user = auth()->user();
        $grades = $grades ?? collect();
        $isPublished = (bool) ($activeYear?->is_published ?? false);
    @endphp

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 no-print">
        <div>
            <h1 class="h3 fw-bold mb-1">Rapor Semester</h1>
            <p class="text-muted small mb-0">
                Rekap nilai akhir, predikat, dan capaian kompetensi per mata pelajaran.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('siswa.dashboard', ['section' => 'dashboard']) }}" class="btn btn-light border rounded-pill px-3 py-2 small fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Dashboard
            </a>
            @if ($isPublished && $grades->isNotEmpty())
                <button type="button" onclick="window.print()" class="btn rd-btn-emerald px-3 py-2 small">
                    <i class="bi bi-printer me-1"></i> Cetak Rapor
                </button>
            @endif
        </div>
    </div>

    <div class="rd-card p-4">
        <div class="print-only-header mb-4 border-bottom pb-3">
            <h2 class="h5 fw-bold mb-1">LAPORAN HASIL BELAJAR PESERTA DIDIK</h2>
            <div class="small text-muted">
                Periode: {{ $activeYear?->name ?? '-' }} ({{ $activeYear?->semester ?? '-' }})
            </div>
        </div>

        <div class="row g-3 mb-4 pb-3 border-bottom">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="text-muted small">Nama Siswa</div>
                <div class="fw-bold text-dark">{{ $user?->name ?? '-' }}</div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="text-muted small">Kelas</div>
                <div class="fw-bold text-dark">{{ $user?->classroom?->name ?? '-' }}</div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="text-muted small">Tahun Ajaran &amp; Semester</div>
                <div class="fw-bold text-dark">{{ $activeYear?->name ?? '-' }} · {{ $activeYear?->semester ?? '-' }}</div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="text-muted small">Status Publikasi</div>
                <div>
                    @if ($isPublished)
                        <span class="badge rounded-pill text-bg-success px-3 py-1">Terbuka</span>
                    @else
                        <span class="badge rounded-pill text-bg-warning text-dark px-3 py-1">Dikunci</span>
                    @endif
                </div>
            </div>
        </div>

        @if (! $isPublished)
            <div class="alert alert-warning border-0 rounded-3 mb-0 small">
                <i class="bi bi-lock-fill me-1"></i>
                Rapor semester belum dipublikasikan oleh Admin Akademik. Silakan cek kembali setelah status publikasi dibuka.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-uppercase text-muted">
                        <tr>
                            <th>Kode</th>
                            <th>Mata Pelajaran</th>
                            <th class="text-center">Tugas</th>
                            <th class="text-center">UTS</th>
                            <th class="text-center">UAS</th>
                            <th class="text-center">Nilai Akhir</th>
                            <th class="text-center">Predikat</th>
                            <th>Deskripsi Capaian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($grades as $grade)
                            <tr>
                                <td>
                                    <span class="badge rounded-pill text-bg-light text-dark border px-2 py-1">
                                        {{ $grade->assignment?->subject?->code ?? '-' }}
                                    </span>
                                </td>
                                <td class="fw-bold text-dark">{{ $grade->assignment?->subject?->name ?? 'Mapel' }}</td>
                                <td class="text-center">{{ $grade->tugas !== null ? number_format((float) $grade->tugas, 2) : '-' }}</td>
                                <td class="text-center">{{ $grade->uts !== null ? number_format((float) $grade->uts, 2) : '-' }}</td>
                                <td class="text-center">{{ $grade->uas !== null ? number_format((float) $grade->uas, 2) : '-' }}</td>
                                <td class="text-center fw-bold text-success">
                                    {{ $grade->nilai_akhir !== null ? number_format((float) $grade->nilai_akhir, 2) : '-' }}
                                </td>
                                <td class="text-center">
                                    @if ($grade->predikat)
                                        <x-grade-badge :predikat="$grade->predikat" />
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $grade->deskripsi ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted small py-4">
                                    Belum ada nilai rapor yang tercatat pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
