@extends('layouts.app')

@section('title', 'Rekap Penugasan Guru')

@section('content')
    @php
        $assignments = $assignments ?? collect();
    @endphp

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Rekap Penugasan</h1>
            <p class="text-muted small mb-0">Ringkasan seluruh penugasan mata pelajaran, kelas, dan periode semester Anda.</p>
        </div>

        <a href="{{ route('guru.dashboard', ['section' => 'dashboard']) }}" class="btn btn-light border rounded-pill px-3 py-2 small fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="rd-card p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h6 fw-bold mb-0">Daftar Penugasan Mengajar</h2>
            <span class="badge rounded-pill text-bg-light border">{{ $assignments->count() }} Penugasan</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-uppercase text-muted">
                    <tr>
                        <th>Kode</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Tahun Ajaran</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assignments as $item)
                        <tr>
                            <td>
                                <span class="badge rounded-pill text-bg-success px-3 py-2">
                                    {{ $item->subject?->code ?? '-' }}
                                </span>
                            </td>
                            <td class="fw-bold text-dark">{{ $item->subject?->name ?? 'Mapel' }}</td>
                            <td>{{ $item->classroom?->name ?? 'Kelas' }}</td>
                            <td class="text-muted small">{{ $item->academicYear?->name ?? '-' }} · {{ $item->academicYear?->semester ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('guru.dashboard', ['section' => 'input-nilai', 'assignment_id' => $item->id]) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-semibold">
                                    Kelola Nilai
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted small py-4">
                                Belum ada data penugasan mengajar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
