<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Rapor Digital') — Rapor Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/rapor-modern.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="rapor-body">
    <div class="d-flex min-vh-100">
        @include('partials.sidebar')

        <div class="flex-grow-1 d-flex flex-column min-w-0">
            @include('partials.navbar')

            <main class="container-fluid px-3 px-lg-4 py-4 flex-grow-1">
                <div class="mb-3">
                    @include('partials.flash-alert')
                </div>

                @yield('content')
            </main>

            <footer class="rapor-footer px-4 py-3 text-muted small d-flex flex-wrap justify-content-between align-items-center gap-2">
                <span>RAPOR DIGITAL — Sistem Penilaian &amp; Rapor Semester Siswa</span>
                <span>{{ $activeYear?->name ?? 'Periode Aktif' }} · {{ $activeYear?->semester ?? '-' }}</span>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/rapor-modern.js') }}"></script>
    @stack('scripts')
</body>
</html>
