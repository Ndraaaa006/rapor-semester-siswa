<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DataMasterController;
use App\Http\Controllers\Admin\PenggunaPenugasanController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\NilaiController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\RaporController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// RUTE UTAMA (ROOT)
Route::get('/', function (Request $request) {
    $user = $request->user();

    if (! $user) {
        return redirect()->route('login');
    }

    return match ($user->role) {
        'admin_akademik' => redirect()->route('admin.dashboard'),
        'guru_mapel' => redirect()->route('guru.dashboard'),
        'siswa_wali' => redirect()->route('siswa.dashboard'),
        default => redirect()->route('login'),
    };
});

// RUTE AUTENTIKASI (LOGIN & LOGOUT)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// BAGIAN ADMIN AKADEMIK
Route::middleware(['auth', 'role:admin_akademik'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard Utama Admin (tetap mendukung parameter ?page=... agar link Blade lama tetap jalan)
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Modul Tahun Ajaran & Publikasi Rapor
        Route::get('/tahun-ajaran', [TahunAjaranController::class, 'index'])->name('academic-years.index');
        Route::post('/academic-years', [TahunAjaranController::class, 'store'])->name('academic-years.store');
        Route::patch('/academic-years/{academicYear}/toggle-publish', [TahunAjaranController::class, 'togglePublish'])->name('academic-years.toggle-publish');

        // Modul Data Master (Kelas & Mata Pelajaran)
        Route::get('/data-master', [DataMasterController::class, 'index'])->name('data-master.index');
        Route::post('/classrooms', [DataMasterController::class, 'storeClassroom'])->name('classrooms.store');
        Route::post('/subjects', [DataMasterController::class, 'storeSubject'])->name('subjects.store');

        // Modul Akun Pengguna & Penugasan Mengajar
        Route::get('/pengguna-penugasan', [PenggunaPenugasanController::class, 'index'])->name('pengguna-penugasan.index');
        Route::post('/users', [PenggunaPenugasanController::class, 'storeUser'])->name('users.store');
        Route::delete('/users/{user}', [PenggunaPenugasanController::class, 'destroyUser'])->name('users.destroy');
        Route::post('/assignments', [PenggunaPenugasanController::class, 'storeAssignment'])->name('assignments.store');
        Route::delete('/assignments/{assignment}', [PenggunaPenugasanController::class, 'destroyAssignment'])->name('assignments.destroy');
    });

// BAGIAN GURU MATA PELAJARAN
Route::middleware(['auth', 'role:guru_mapel'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
        Route::post('/assignments/{assignment}/grades', [NilaiController::class, 'store'])->name('grades.save');
    });

// BAGIAN SISWA / WALI
Route::middleware(['auth', 'role:siswa_wali'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/rapor', [RaporController::class, 'index'])->name('rapor.index');
    });
