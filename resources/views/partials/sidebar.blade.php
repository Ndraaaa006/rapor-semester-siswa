@php
    $role = auth()->user()?->role;
    $currentSection = $activeSection ?? $activePage ?? 'dashboard';
@endphp

<aside class="rd-sidebar d-none d-lg-flex flex-column justify-content-between p-3">
    <div>
        <div class="d-flex align-items-center gap-2 px-2 py-2 mb-3">
            <span class="rd-brand-mark">
                <i class="bi bi-journal-check"></i>
            </span>
            <div>
                <div class="fw-bold fs-6 lh-1">Rapor Digital</div>
                <div class="text-muted" style="font-size: 0.72rem;">Penilaian Semester</div>
            </div>
        </div>

        <div class="text-uppercase fw-semibold text-muted px-2 mb-2" style="font-size: 0.68rem; letter-spacing: 0.08em;">
            Menu
        </div>

        <div class="d-flex flex-column gap-1">
            @if ($role === 'admin_akademik')
                <a href="{{ route('admin.dashboard', ['page' => 'dashboard']) }}"
                   class="rd-nav-link {{ $currentSection === 'dashboard' ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.dashboard', ['page' => 'tahun-ajaran']) }}"
                   class="rd-nav-link {{ $currentSection === 'tahun-ajaran' ? 'active' : '' }}">
                    <i class="bi bi-calendar2-check"></i>
                    <span>Tahun Ajaran</span>
                </a>
                <a href="{{ route('admin.dashboard', ['page' => 'data-master']) }}"
                   class="rd-nav-link {{ $currentSection === 'data-master' ? 'active' : '' }}">
                    <i class="bi bi-collection"></i>
                    <span>Kelas &amp; Mapel</span>
                </a>
                <a href="{{ route('admin.dashboard', ['page' => 'pengguna-penugasan']) }}"
                   class="rd-nav-link {{ $currentSection === 'pengguna-penugasan' ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Akun &amp; Penugasan</span>
                </a>
            @elseif ($role === 'guru_mapel')
                <a href="{{ route('guru.dashboard', ['section' => 'dashboard']) }}"
                   class="rd-nav-link {{ $currentSection === 'dashboard' ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('guru.dashboard', ['section' => 'input-nilai']) }}"
                   class="rd-nav-link {{ $currentSection === 'input-nilai' ? 'active' : '' }}">
                    <i class="bi bi-pencil-square"></i>
                    <span>Input Nilai</span>
                </a>
                <a href="{{ route('guru.dashboard', ['section' => 'rekap-penugasan']) }}"
                   class="rd-nav-link {{ $currentSection === 'rekap-penugasan' ? 'active' : '' }}">
                    <i class="bi bi-list-check"></i>
                    <span>Rekap Penugasan</span>
                </a>
            @elseif ($role === 'siswa_wali')
                <a href="{{ route('siswa.dashboard', ['section' => 'dashboard']) }}"
                   class="rd-nav-link {{ $currentSection === 'dashboard' ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('siswa.dashboard', ['section' => 'rapor-semester']) }}"
                   class="rd-nav-link {{ $currentSection === 'rapor-semester' ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Rapor Semester</span>
                </a>
            @endif
        </div>

        <div class="text-uppercase fw-semibold text-muted px-2 mt-4 mb-2" style="font-size: 0.68rem; letter-spacing: 0.08em;">
            Sistem
        </div>

        <div class="d-flex flex-column gap-1">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rd-nav-link w-100 border-0 bg-transparent text-start">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </div>

    <div class="rd-kpi-featured p-3 mt-4">
        <div class="small fw-bold mb-1">Periode Akademik</div>
        <div class="small text-white-50 mb-2">
            {{ $activeYear?->name ?? 'Belum diatur' }} · {{ $activeYear?->semester ?? '-' }}
        </div>
        <span class="badge rounded-pill {{ $activeYear?->is_published ? 'bg-light text-success' : 'bg-warning text-dark' }}">
            {{ $activeYear?->is_published ? 'Rapor Terbuka' : 'Rapor Dikunci' }}
        </span>
    </div>
</aside>
