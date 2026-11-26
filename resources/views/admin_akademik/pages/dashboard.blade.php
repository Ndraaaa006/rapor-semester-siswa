@extends('layouts.app')

@section('title', 'Dashboard Admin Akademik')

@section('content')
    @php
        $years = $academicYears ?? collect();
        $classes = $classrooms ?? collect();
        $subjects = $subjects ?? collect();
        $teachers = $teachers ?? collect();
        $students = $students ?? collect();
        $assignments = $assignments ?? collect();
        $currentYear = $activeYear ?? $years->firstWhere('is_active', true);
    @endphp

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Dashboard Admin</h1>
            <p class="text-muted small mb-0">Kelola periode akademik, data master, akun pengguna, dan publikasi rapor.</p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.dashboard', ['page' => 'tahun-ajaran']) }}" class="btn rd-btn-emerald px-3 py-2 small">
                <i class="bi bi-plus-lg me-1"></i> Atur Tahun Ajaran
            </a>
            <a href="{{ route('admin.dashboard', ['page' => 'pengguna-penugasan']) }}" class="btn btn-light border rounded-pill px-3 py-2 small fw-semibold">
                Kelola Akun &amp; Jadwal
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stat-card
                label="Tahun Ajaran Aktif"
                :value="$currentYear?->name ?? '-'"
                :meta="$currentYear ? 'Semester ' . $currentYear->semester : 'Belum diatur'"
                :href="route('admin.dashboard', ['page' => 'tahun-ajaran'])"
                :featured="true"
            />
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stat-card
                label="Total Kelas"
                :value="$classes->count()"
                :meta="$students->count() . ' akun siswa terdaftar'"
                :href="route('admin.dashboard', ['page' => 'data-master'])"
            />
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stat-card
                label="Total Mata Pelajaran"
                :value="$subjects->count()"
                :meta="$teachers->count() . ' akun guru mapel'"
                :href="route('admin.dashboard', ['page' => 'data-master'])"
            />
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stat-card
                label="Publikasi Rapor"
                :value="$currentYear?->is_published ? 'Terbuka' : 'Dikunci'"
                meta="Akses rapor semester siswa"
                :href="route('admin.dashboard', ['page' => 'tahun-ajaran'])"
            />
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <div class="rd-card p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h2 class="h6 fw-bold mb-0">Penugasan Mengajar Terbaru</h2>
                    <a href="{{ route('admin.dashboard', ['page' => 'pengguna-penugasan']) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                        + Tambah
                    </a>
                </div>

                <div class="d-flex flex-column gap-2">
                    @forelse ($assignments->take(5) as $item)
                        <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light">
                            <div>
                                <div class="fw-semibold small">{{ $item->teacher?->name ?? 'Guru' }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    {{ $item->subject?->name ?? 'Mapel' }} · {{ $item->classroom?->name ?? 'Kelas' }}
                                </div>
                            </div>
                            <span class="badge rounded-pill text-bg-success-subtle text-success border border-success-subtle">
                                {{ $item->academicYear?->semester ?? 'Aktif' }}
                            </span>
                        </div>
                    @empty
                        <div class="text-muted small py-3 text-center">Belum ada penugasan mengajar.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="rd-card p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h2 class="h6 fw-bold mb-0">Akses Cepat Modul</h2>
                    <span class="badge rounded-pill text-bg-light border">3 Peran</span>
                </div>

                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('admin.dashboard', ['page' => 'tahun-ajaran']) }}" class="d-flex align-items-center justify-content-between p-3 rounded-3 border text-decoration-none text-dark">
                        <div>
                            <div class="fw-semibold small">Tahun Ajaran &amp; Publikasi Rapor</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Buka atau kunci akses rapor semester siswa</div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    <a href="{{ route('admin.dashboard', ['page' => 'data-master']) }}" class="d-flex align-items-center justify-content-between p-3 rounded-3 border text-decoration-none text-dark">
                        <div>
                            <div class="fw-semibold small">Data Kelas &amp; Mata Pelajaran</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Kelola rombongan belajar dan kode mapel</div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                    <a href="{{ route('admin.dashboard', ['page' => 'pengguna-penugasan']) }}" class="d-flex align-items-center justify-content-between p-3 rounded-3 border text-decoration-none text-dark">
                        <div>
                            <div class="fw-semibold small">Akun Guru, Siswa &amp; Jadwal Ajar</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Registrasi akun dan pemetaan kelas ajar</div>
                        </div>
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
