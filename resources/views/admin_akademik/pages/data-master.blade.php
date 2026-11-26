@extends('layouts.app')

@section('title', 'Kelas & Mata Pelajaran')

@section('content')
    @php
        $classes = $classrooms ?? collect();
        $subjects = $subjects ?? collect();
    @endphp

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Kelas &amp; Mata Pelajaran</h1>
            <p class="text-muted small mb-0">Kelola rombongan belajar dan daftar mata pelajaran yang digunakan pada penugasan guru serta rapor.</p>
        </div>

        <a href="{{ route('admin.dashboard', ['page' => 'dashboard']) }}" class="btn btn-light border rounded-pill px-3 py-2 small fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    {{-- Banner & Ringkasan Data Master --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-6">
            <div class="rd-kpi-featured p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <span class="badge rounded-pill bg-white text-success px-3 py-1 fw-semibold mb-2">
                        Master Akademik
                    </span>
                    <h2 class="h5 fw-bold mb-1">Rombongan Belajar &amp; Mapel</h2>
                    <p class="text-white-50 small mb-0">
                        Tambahkan kelas per tingkat serta kode dan nama mata pelajaran resmi sekolah.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="rd-card p-4 h-100 d-flex flex-column justify-content-between">
                <div class="fw-semibold small text-dark mb-2">Total Kelas</div>
                <div class="fs-2 fw-bold lh-1 text-success">{{ $classes->count() }}</div>
                <div class="text-muted small mt-2">Rombongan belajar aktif</div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="rd-card p-4 h-100 d-flex flex-column justify-content-between">
                <div class="fw-semibold small text-dark mb-2">Total Mapel</div>
                <div class="fs-2 fw-bold lh-1 text-dark">{{ $subjects->count() }}</div>
                <div class="text-muted small mt-2">Mata pelajaran terdaftar</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Kolom Kiri: Data Kelas --}}
        <div class="col-12 col-lg-6">
            <div class="rd-card p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h2 class="h6 fw-bold mb-1">Data Kelas</h2>
                        <p class="text-muted small mb-0">Tambah kelas dan tingkat rombongan belajar.</p>
                    </div>
                    <span class="badge rounded-pill text-bg-success-subtle text-success border border-success-subtle px-3 py-2">
                        {{ $classes->count() }} Kelas
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.classrooms.store') }}" class="row g-2 align-items-end mb-4">
                    @csrf

                    <div class="col-12 col-sm-5">
                        <label for="class_name" class="form-label fw-semibold small">Nama Kelas</label>
                        <input
                            id="class_name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="Contoh: X RPL 1"
                            class="form-control"
                        >
                    </div>

                    <div class="col-12 col-sm-4">
                        <label for="class_level" class="form-label fw-semibold small">Tingkat</label>
                        <input
                            id="class_level"
                            type="text"
                            name="level"
                            value="{{ old('level') }}"
                            required
                            placeholder="X / XI / XII"
                            class="form-control"
                        >
                    </div>

                    <div class="col-12 col-sm-3">
                        <button type="submit" class="btn rd-btn-emerald w-100 py-2 small">
                            <i class="bi bi-plus-lg me-1"></i> Tambah
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-uppercase text-muted">
                            <tr>
                                <th>Nama Kelas</th>
                                <th>Tingkat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($classes as $class)
                                <tr>
                                    <td class="fw-bold text-dark">{{ $class->name }}</td>
                                    <td>
                                        <span class="badge rounded-pill text-bg-light text-dark border px-3 py-2">
                                            {{ $class->level }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted small py-4">
                                        Belum ada data kelas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Data Mata Pelajaran --}}
        <div class="col-12 col-lg-6">
            <div class="rd-card p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h2 class="h6 fw-bold mb-1">Data Mata Pelajaran</h2>
                        <p class="text-muted small mb-0">Tambah kode dan nama mata pelajaran.</p>
                    </div>
                    <span class="badge rounded-pill text-bg-success-subtle text-success border border-success-subtle px-3 py-2">
                        {{ $subjects->count() }} Mapel
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.subjects.store') }}" class="row g-2 align-items-end mb-4">
                    @csrf

                    <div class="col-12 col-sm-3">
                        <label for="subject_code" class="form-label fw-semibold small">Kode</label>
                        <input
                            id="subject_code"
                            type="text"
                            name="code"
                            value="{{ old('code') }}"
                            required
                            placeholder="MTK / PWB"
                            class="form-control"
                        >
                    </div>

                    <div class="col-12 col-sm-6">
                        <label for="subject_name" class="form-label fw-semibold small">Nama Mata Pelajaran</label>
                        <input
                            id="subject_name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="Nama Mata Pelajaran"
                            class="form-control"
                        >
                    </div>

                    <div class="col-12 col-sm-3">
                        <button type="submit" class="btn rd-btn-emerald w-100 py-2 small">
                            <i class="bi bi-plus-lg me-1"></i> Tambah
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-uppercase text-muted">
                            <tr>
                                <th>Kode</th>
                                <th>Mata Pelajaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subjects as $subject)
                                <tr>
                                    <td>
                                        <span class="badge rounded-pill text-bg-success px-3 py-2">
                                            {{ $subject->code }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold text-dark">{{ $subject->name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted small py-4">
                                        Belum ada mata pelajaran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
