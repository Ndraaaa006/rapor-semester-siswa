@php
    $user = auth()->user();
    $roleLabel = match ($user?->role) {
        'admin_akademik' => 'Admin Akademik',
        'guru_mapel' => 'Guru Mata Pelajaran',
        'siswa_wali' => 'Siswa / Wali',
        default => 'Pengguna',
    };
@endphp

<header class="rd-topbar sticky-top px-3 px-lg-4 py-3">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3 flex-grow-1">
            
        </div>

        <div class="d-flex align-items-center gap-3">
            @yield('header_actions')

            <div class="d-flex align-items-center gap-2">
                <span class="rd-avatar">{{ strtoupper(substr($user?->name ?? 'RD', 0, 2)) }}</span>
                <div class="d-none d-sm-block">
                    <div class="fw-semibold small lh-sm">{{ $user?->name }}</div>
                    <div class="text-muted" style="font-size: 0.72rem;">{{ $roleLabel }}</div>
                </div>
            </div>
        </div>
    </div>
</header>
