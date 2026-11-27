<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $page = $request->query('page', 'dashboard');

        if ($page === 'tahun-ajaran') {
            return app(TahunAjaranController::class)->index();
        }

        if ($page === 'data-master') {
            return app(DataMasterController::class)->index();
        }

        if ($page === 'pengguna-penugasan') {
            return app(PenggunaPenugasanController::class)->index();
        }

        $academicYears = AcademicYear::query()
            ->orderByDesc('is_active')
            ->orderByDesc('id')
            ->get();

        $activeYear = $academicYears->firstWhere('is_active', true);
        $classrooms = Classroom::query()->orderBy('name')->get();
        $subjects = Subject::query()->orderBy('name')->get();
        $teachers = User::query()->where('role', 'guru_mapel')->orderBy('name')->get();
        $students = User::query()->where('role', 'siswa_wali')->orderBy('name')->get();

        $assignments = TeachingAssignment::query()
            ->with(['teacher', 'subject', 'classroom', 'academicYear'])
            ->orderByDesc('id')
            ->get();

        return view('admin_akademik.pages.dashboard', [
            'activePage' => 'dashboard',
            'activeSection' => 'dashboard',
            'academicYears' => $academicYears,
            'activeYear' => $activeYear,
            'classrooms' => $classrooms,
            'subjects' => $subjects,
            'teachers' => $teachers,
            'students' => $students,
            'assignments' => $assignments,
        ]);
    }
}
