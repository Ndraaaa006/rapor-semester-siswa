<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruDashboardController;
use App\Http\Controllers\SiswaDashboardController;
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
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/academic-years', [AdminDashboardController::class, 'storeAcademicYear'])->name('academic-years.store');
        Route::patch('/academic-years/{academicYear}/toggle-publish', [AdminDashboardController::class, 'togglePublish'])->name('academic-years.toggle-publish');
        Route::post('/classrooms', [AdminDashboardController::class, 'storeClassroom'])->name('classrooms.store');
        Route::post('/subjects', [AdminDashboardController::class, 'storeSubject'])->name('subjects.store');
        Route::post('/users', [AdminDashboardController::class, 'storeUser'])->name('users.store');
        Route::post('/assignments', [AdminDashboardController::class, 'storeAssignment'])->name('assignments.store');
        Route::delete('/users/{user}', [AdminDashboardController::class, 'destroyUser'])->name('users.destroy');
        Route::delete('/assignments/{assignment}', [AdminDashboardController::class, 'destroyAssignment'])->name('assignments.destroy');
    });

// BAGIAN GURU MATA PELAJARAN
Route::middleware(['auth', 'role:guru_mapel'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
        Route::post('/assignments/{assignment}/grades', [GuruDashboardController::class, 'saveGrades'])->name('grades.save');
    });

// BAGIAN SISWA / WALI
Route::middleware(['auth', 'role:siswa_wali'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    });
