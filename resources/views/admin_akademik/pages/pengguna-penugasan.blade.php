@extends('layouts.app')

@section('title', 'Akun & Penugasan Guru')

@section('content')
    @php
        $years = $academicYears ?? collect();
        $classes = $allClassrooms ?? ($classrooms ?? collect());
        $subjects = $allSubjects ?? ($subjects ?? collect());
        $teachers = $teachers ?? collect();
        $students = $students ?? collect();
        $assignments = $assignments ?? collect();
    @endphp

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Akun &amp; Penugasan Guru</h1>
            <p class="text-muted small mb-0">Daftarkan akun Guru Mata Pelajaran atau Siswa / Wali dan hubungkan guru ke kelas, mapel, serta periode ajaran.</p>
        </div>

        <a href="{{ route('admin.dashboard', ['page' => 'dashboard']) }}" class="btn btn-light border rounded-pill px-3 py-2 small fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    {{-- Banner & Ringkasan Akun / Penugasan --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-xl-5">
            <div class="rd-kpi-featured p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <span class="badge rounded-pill bg-white text-success px-3 py-1 fw-semibold mb-2">
                        Pengguna &amp; Jadwal Ajar
                    </span>
                    <h2 class="h5 fw-bold mb-1">Manajemen Akun &amp; Penugasan</h2>
                    <p class="text-white-50 small mb-0">
                        Kelola kredensial masuk guru dan siswa serta tetapkan penugasan mengajar per semester.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-4 col-xl-2">
            <div class="rd-card p-4 h-100 d-flex flex-column justify-content-between">
                <div class="fw-semibold small text-dark mb-2">Guru Mapel</div>
                <div class="fs-2 fw-bold lh-1 text-success">{{ $teachers->count() }}</div>
                <div class="text-muted small mt-2">Akun pengajar</div>
            </div>
        </div>

        <div class="col-4 col-xl-2">
            <div class="rd-card p-4 h-100 d-flex flex-column justify-content-between">
                <div class="fw-semibold small text-dark mb-2">Siswa / Wali</div>
                <div class="fs-2 fw-bold lh-1 text-dark">{{ $students->count() }}</div>
                <div class="text-muted small mt-2">Akun peserta didik</div>
            </div>
        </div>

        <div class="col-4 col-xl-3">
            <div class="rd-card p-4 h-100 d-flex flex-column justify-content-between">
                <div class="fw-semibold small text-dark mb-2">Penugasan</div>
                <div class="fs-2 fw-bold lh-1 text-success">{{ $assignments->count() }}</div>
                <div class="text-muted small mt-2">Jadwal mengajar aktif</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Kolom Kiri: Registrasi Akun & Daftar Akun --}}
        <div class="col-12 col-lg-6">
            <div class="rd-card p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h2 class="h6 fw-bold mb-1">Registrasi Akun Guru &amp; Siswa / Wali</h2>
                            <p class="text-muted small mb-0">Buat akun baru dengan peran terstruktur dan kelas siswa.</p>
                        </div>
                        <span class="badge rounded-pill text-bg-success-subtle text-success border border-success-subtle px-3 py-2">
                            Akun Baru
                        </span>
                    </div>

                    <form method="POST" action="{{ route('admin.users.store') }}" class="row g-3 mb-4">
                        @csrf

                        <div class="col-12 col-sm-6">
                            <label for="user_name" class="form-label fw-semibold small">Nama Lengkap</label>
                            <input
                                id="user_name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                placeholder="Nama Lengkap"
                                class="form-control"
                            >
                        </div>

                        <div class="col-12 col-sm-6">
                            <label for="user_email" class="form-label fw-semibold small">Email Akun</label>
                            <input
                                id="user_email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                placeholder="nama@sekolah.sch.id"
                                class="form-control"
                            >
                        </div>

                        <div class="col-12 col-sm-4">
                            <label for="user_password" class="form-label fw-semibold small">Kata Sandi</label>
                            <input
                                id="user_password"
                                type="password"
                                name="password"
                                required
                                minlength="8"
                                placeholder="Minimal 8 karakter"
                                class="form-control"
                            >
                        </div>

                        <div class="col-12 col-sm-4">
                            <label for="user_role" class="form-label fw-semibold small">Peran Pengguna</label>
                            <select id="user_role" name="role" class="form-select">
                                <option value="guru_mapel" @selected(old('role') === 'guru_mapel')>Guru Mata Pelajaran</option>
                                <option value="siswa_wali" @selected(old('role') === 'siswa_wali')>Siswa / Wali</option>
                            </select>
                        </div>

                        <div class="col-12 col-sm-4">
                            <label for="user_classroom" class="form-label fw-semibold small">Kelas Siswa</label>
                            <select id="user_classroom" name="classroom_id" class="form-select">
                                <option value="">Opsional (Khusus Siswa)</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}" @selected(old('classroom_id') == $class->id)>{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn rd-btn-emerald w-100 py-2 small">
                                <i class="bi bi-person-plus me-1"></i> Simpan Akun Pengguna
                            </button>
                        </div>
                    </form>
                </div>

                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-bold small">Ringkasan Akun Terdaftar</span>
                        <span class="badge rounded-pill text-bg-light border">{{ $teachers->count() + $students->count() }} Akun</span>
                    </div>

                    <div class="list-group list-group-flush border rounded-3" style="max-height: 240px; overflow-y: auto;">
                        @foreach ($teachers as $teacher)
                            <div class="list-group-item d-flex align-items-center justify-content-between gap-2 py-2 px-3">
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <span class="fw-semibold small text-dark">{{ $teacher->name }}</span>
                                    <span class="badge rounded-pill text-bg-success-subtle text-success border border-success-subtle">
                                        Guru Mapel
                                    </span>
                                </div>

                                @if (auth()->id() !== $teacher->id)
                                    <form method="POST" action="{{ route('admin.users.destroy', $teacher->id) }}"
                                          onsubmit="return confirm('Hapus akun guru {{ addslashes($teacher->name) }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1 small" title="Hapus akun">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach

                        @foreach ($students as $student)
                            <div class="list-group-item d-flex align-items-center justify-content-between gap-2 py-2 px-3">
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <span class="fw-semibold small text-dark">{{ $student->name }}</span>
                                    <span class="badge rounded-pill text-bg-light text-dark border">
                                        Siswa · {{ $student->classroom?->name ?? '-' }}
                                    </span>
                                </div>

                                @if (auth()->id() !== $student->id)
                                    <form method="POST" action="{{ route('admin.users.destroy', $student->id) }}"
                                          onsubmit="return confirm('Hapus akun siswa {{ addslashes($student->name) }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1 small" title="Hapus akun">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Pemetaan Penugasan Mengajar & Penugasan Terdaftar --}}
        <div class="col-12 col-lg-6">
            <div class="rd-card p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h2 class="h6 fw-bold mb-1">Pemetaan Penugasan Mengajar</h2>
                            <p class="text-muted small mb-0">Hubungkan guru mata pelajaran ke kelas dan periode ajaran.</p>
                        </div>
                        <span class="badge rounded-pill text-bg-success-subtle text-success border border-success-subtle px-3 py-2">
                            {{ $assignments->count() }} Jadwal
                        </span>
                    </div>

                    <form method="POST" action="{{ route('admin.assignments.store') }}" class="row g-3 mb-4">
                        @csrf

                        <div class="col-12 col-sm-6">
                            <label for="assign_teacher" class="form-label fw-semibold small">Guru Mata Pelajaran</label>
                            <select id="assign_teacher" name="teacher_id" required class="form-select">
                                <option value="">Pilih Guru Mapel</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label for="assign_subject" class="form-label fw-semibold small">Mata Pelajaran</label>
                            <select id="assign_subject" name="subject_id" required class="form-select">
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label for="assign_classroom" class="form-label fw-semibold small">Kelas</label>
                            <select id="assign_classroom" name="classroom_id" required class="form-select">
                                <option value="">Pilih Kelas</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label for="assign_year" class="form-label fw-semibold small">Tahun Ajaran</label>
                            <select id="assign_year" name="academic_year_id" required class="form-select">
                                <option value="">Pilih Tahun Ajaran</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }} ({{ $year->semester }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn rd-btn-emerald w-100 py-2 small">
                                <i class="bi bi-calendar-plus me-1"></i> Simpan Penugasan Mengajar
                            </button>
                        </div>
                    </form>
                </div>

                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-bold small">Penugasan Terdaftar ({{ $assignments->count() }})</span>
                    </div>

                    @if ($assignments->isNotEmpty())
                        <div class="list-group list-group-flush border rounded-3" style="max-height: 240px; overflow-y: auto;">
                            @foreach ($assignments as $item)
                                <div class="list-group-item d-flex align-items-center justify-content-between gap-2 py-2 px-3">
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <div>
                                            <span class="fw-bold small text-dark">{{ $item->teacher?->name ?? 'Guru' }}</span>
                                            <span class="text-muted small">· {{ $item->subject?->name ?? 'Mapel' }}</span>
                                        </div>
                                        <span class="badge rounded-pill text-bg-success-subtle text-success border border-success-subtle">
                                            {{ $item->classroom?->name ?? 'Kelas' }}
                                        </span>
                                    </div>

                                    <form method="POST" action="{{ route('admin.assignments.destroy', $item->id) }}"
                                          onsubmit="return confirm('Hapus penugasan mengajar ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1 small" title="Hapus penugasan">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="border rounded-3 p-3 text-center text-muted small">
                            Belum ada penugasan mengajar terdaftar.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
