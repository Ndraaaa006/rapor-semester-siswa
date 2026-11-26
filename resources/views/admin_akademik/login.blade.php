<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk — Rapor Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/rapor-modern.css') }}">
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
    <div class="grid min-h-screen lg:grid-cols-12">
        {{-- Panel Kiri: Brand & Keunggulan Sistem --}}
        <div class="relative flex flex-col justify-between overflow-hidden bg-slate-950 p-8 text-white sm:p-12 lg:col-span-6 xl:col-span-7">
            <div class="pointer-events-none absolute -top-24 -left-24 h-80 w-80 rounded-full bg-emerald-500/15 blur-3xl"></div>
            <div class="pointer-events-none absolute right-0 bottom-0 h-96 w-96 rounded-full bg-teal-400/10 blur-3xl"></div>

            <div class="relative z-10 flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-500 text-slate-950 shadow-lg shadow-emerald-500/25">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-base font-extrabold tracking-tight">RAPOR DIGITAL</div>
                        <div class="text-xs text-slate-400">Sistem Penilaian &amp; Rapor Semester</div>
                    </div>
                </div>

                <span class="hidden rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-300 sm:inline-flex">
                    Akademik Terpadu
                </span>
            </div>

            <div class="relative z-10 my-12 max-w-xl">
                <h1 class="text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl xl:text-5xl">
                    Kelola Nilai &amp; Rapor Siswa Lebih <span class="text-emerald-400">Cepat, Rapi &amp; Akurat</span>
                </h1>
                <p class="mt-4 text-sm leading-relaxed text-slate-300 sm:text-base">
                    Satu platform terintegrasi untuk pengaturan periode akademik, penginputan nilai komponen oleh guru, finalisasi &amp; publikasi rapor, hingga rekap cetak bagi siswa dan wali.
                </p>

                <div class="mt-8 grid gap-3.5 sm:grid-cols-3">
                    <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-4 backdrop-blur-sm">
                        <div class="text-xs font-bold text-emerald-400">3 Hak Akses</div>
                        <div class="mt-1 text-xs text-slate-300">Admin, Guru Mapel &amp; Siswa/Wali</div>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-4 backdrop-blur-sm">
                        <div class="text-xs font-bold text-emerald-400">Nilai &amp; Predikat</div>
                        <div class="mt-1 text-xs text-slate-300">Kalkulasi otomatis &amp; finalisasi nilai</div>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-4 backdrop-blur-sm">
                        <div class="text-xs font-bold text-emerald-400">Cetak Rapor</div>
                        <div class="mt-1 text-xs text-slate-300">Siap cetak setelah dipublikasikan</div>
                    </div>
                </div>
            </div>

            <div class="relative z-10 flex flex-col justify-between gap-2 border-t border-slate-800/90 pt-5 text-xs text-slate-400 sm:flex-row sm:items-center">
                <span>Autentikasi sesi aman &amp; pembatasan hak akses per peran</span>
                <span class="text-slate-500">RAPOR DIGITAL</span>
            </div>
        </div>

        {{-- Panel Kanan: Form Masuk --}}
        <div class="flex items-center justify-center bg-gradient-to-b from-slate-50 to-slate-100 p-6 sm:p-10 lg:col-span-6 xl:col-span-5">
            <div class="w-full max-w-md rounded-3xl border border-slate-200/90 bg-white p-7 shadow-xl shadow-slate-900/5 sm:p-9">
                <div class="mb-7">
                    <div class="inline-flex items-center gap-2 rounded-lg bg-emerald-50 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider text-emerald-700">
                        <span>Autentikasi Akun</span>
                    </div>
                    <h2 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-900">Masuk ke Akun</h2>
                    <p class="mt-1 text-xs leading-relaxed text-slate-500">
                        Gunakan email dan kata sandi resmi yang terdaftar di sistem akademik.
                    </p>
                </div>

                @include('partials.flash-alert')

                <form method="POST" action="{{ route('login.submit') }}" class="mt-4 space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-1.5 block text-xs font-semibold text-slate-700">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="email"
                                placeholder="nama@sekolah.sch.id"
                                class="w-full rounded-xl border border-slate-300 bg-slate-50/60 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-600 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-600/10"
                            >
                        </div>
                    </div>

                    <div>
                        <div class="mb-1.5 flex items-center justify-between">
                            <label for="password" class="block text-xs font-semibold text-slate-700">
                                Kata Sandi
                            </label>
                        </div>
                        <div class="relative">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan kata sandi"
                                class="w-full rounded-xl border border-slate-300 bg-slate-50/60 py-2.5 pr-16 pl-3.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-600 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-600/10"
                            >
                            <button
                                type="button"
                                data-toggle-password="#password"
                                class="absolute top-1/2 right-3 -translate-y-1/2 rounded-md px-2 py-1 text-[11px] font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-slate-800"
                            >
                                Lihat
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-0.5">
                        <label class="inline-flex cursor-pointer items-center gap-2 text-xs font-medium text-slate-600">
                            <input
                                type="checkbox"
                                name="remember"
                                value="1"
                                @checked(old('remember'))
                                class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                            >
                            <span>Ingat sesi ini</span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-3 text-sm font-bold text-white shadow-md shadow-slate-900/15 transition hover:bg-emerald-600 hover:shadow-emerald-600/20 focus:outline-none focus:ring-4 focus:ring-emerald-600/20"
                    >
                        <span>Masuk ke Sistem</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/rapor-modern.js') }}"></script>
</body>
</html>
