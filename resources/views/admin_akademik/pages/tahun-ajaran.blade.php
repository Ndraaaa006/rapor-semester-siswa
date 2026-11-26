@extends('layouts.app')

@section('title', 'Tahun Ajaran & Publikasi Rapor')

@section('content')
    @php
        $years = $academicYears ?? collect();
        $activeCount = $years->where('is_active', true)->count();
        $publishedCount = $years->where('is_published', true)->count();
    @endphp

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Tahun Ajaran &amp; Publikasi Rapor</h1>
            <p class="text-muted small mb-0">Atur periode semester aktif dan buka/kunci akses rapor untuk Siswa / Wali.</p>
        </div>

        <a href="{{ route('admin.dashboard', ['page' => 'dashboard']) }}" class="btn btn-light border rounded-pill px-3 py-2 small fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    {{-- Banner & Ringkasan Periode --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-6">
            <div class="rd-kpi-featured p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <span class="badge rounded-pill bg-white text-success px-3 py-1 fw-semibold mb-2">
                        Periode Akademik
                    </span>
                    <h2 class="h5 fw-bold mb-1">Manajemen Tahun Ajaran &amp; Rapor</h2>
                    <p class="text-white-50 small mb-0">
                        Tentukan tahun ajaran dan semester aktif berjalan serta kontrol akses publikasi rapor semester.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="rd-card p-4 h-100 d-flex flex-column justify-content-between">
                <div class="fw-semibold small text-dark mb-2">Periode Aktif</div>
                <div class="fs-2 fw-bold lh-1 text-success">{{ $activeCount }}</div>
                <div class="text-muted small mt-2">Semester berjalan saat ini</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="rd-card p-4 h-100 d-flex flex-column justify-content-between">
                <div class="fw-semibold small text-dark mb-2">Rapor Terbuka</div>
                <div class="fs-2 fw-bold lh-1 text-dark">{{ $publishedCount }}</div>
                <div class="text-muted small mt-2">Dapat diakses siswa &amp; wali</div>
            </div>
        </div>
    </div>

    {{-- Form Tambah / Atur Periode --}}
    <div class="rd-card p-4 mb-4">
        <div class="mb-3">
            <h2 class="h6 fw-bold mb-1">Tambah / Atur Periode Akademik Baru</h2>
            <p class="text-muted small mb-0">Isi tahun ajaran, pilih semester, lalu tentukan status aktif dan publikasi rapor.</p>
        </div>

        <form method="POST" action="{{ route('admin.academic-years.store') }}" class="row g-3 align-items-end">
            @csrf

            <div class="col-12 col-md-4">
                <label for="year_name" class="form-label fw-semibold small">Tahun Ajaran</label>
                <input
                    id="year_name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    placeholder="Contoh: 2026/2027"
                    class="form-control"
                >
            </div>

            <div class="col-12 col-md-3">
                <label for="year_semester" class="form-label fw-semibold small">Semester</label>
                <select id="year_semester" name="semester" class="form-select">
                    <option value="Ganjil" @selected(old('semester', 'Ganjil') === 'Ganjil')>Ganjil</option>
                    <option value="Genap" @selected(old('semester') === 'Genap')>Genap</option>
                </select>
            </div>

            <div class="col-12 col-md-3">
                <div class="d-flex flex-column gap-2 pb-1">
                    <div class="form-check mb-0">
                        <input id="is_active" type="checkbox" name="is_active" value="1" @checked(old('is_active')) class="form-check-input">
                        <label for="is_active" class="form-check-label small fw-semibold">Jadikan Aktif</label>
                    </div>
                    <div class="form-check mb-0">
                        <input id="is_published" type="checkbox" name="is_published" value="1" @checked(old('is_published')) class="form-check-input">
                        <label for="is_published" class="form-check-label small fw-semibold">Publikasikan Rapor</label>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-2">
                <button type="submit" class="btn rd-btn-emerald w-100 py-2 small">
                    <i class="bi bi-check-lg me-1"></i> Simpan Periode
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Daftar Tahun Ajaran --}}
    <div class="rd-card p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h6 fw-bold mb-0">Daftar Periode Akademik</h2>
            <span class="badge rounded-pill text-bg-light border">{{ $years->count() }} Periode</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small text-uppercase text-muted">
                    <tr>
                        <th>Tahun Ajaran</th>
                        <th>Semester</th>
                        <th>Status Aktif</th>
                        <th>Publikasi Rapor</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($years as $year)
                        <tr>
                            <td class="fw-bold text-dark">{{ $year->name }}</td>
                            <td>{{ $year->semester }}</td>
                            <td>
                                @if ($year->is_active)
                                    <span class="badge rounded-pill text-bg-success-subtle text-success border border-success-subtle px-3 py-2">
                                        Aktif
                                    </span>
                                @else
                                    <span class="badge rounded-pill text-bg-light text-secondary border px-3 py-2">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if ($year->is_published)
                                    <span class="badge rounded-pill text-bg-success px-3 py-2">
                                        Terbuka
                                    </span>
                                @else
                                    <span class="badge rounded-pill text-bg-warning text-dark px-3 py-2">
                                        Dikunci
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <form
                                    method="POST"
                                    action="{{ route('admin.academic-years.toggle-publish', $year->id) }}"
                                    data-confirm-message="Ubah status publikasi rapor untuk periode ini?"
                                    class="d-inline"
                                >
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        class="btn btn-sm rounded-pill px-3 fw-semibold {{ $year->is_published ? 'btn-outline-warning text-dark' : 'btn-outline-success' }}"
                                    >
                                        {{ $year->is_published ? 'Kunci Rapor' : 'Buka Rapor' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted small py-4">
                                Belum ada data tahun ajaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
