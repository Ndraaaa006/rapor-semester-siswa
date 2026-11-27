<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $teacherId = $request->user()?->id;

        $allowedSections = ['dashboard', 'input-nilai', 'rekap-penugasan'];
        $section = $request->query('section', 'input-nilai');
        if (! in_array($section, $allowedSections, true)) {
            $section = 'input-nilai';
        }

        $activeYear = AcademicYear::query()
            ->where('is_active', true)
            ->first();

        $assignments = TeachingAssignment::query()
            ->with(['classroom', 'subject', 'academicYear'])
            ->where('teacher_id', $teacherId)
            ->orderByDesc('id')
            ->get();

        $selectedAssignment = null;
        $students = collect();
        $gradesByStudent = collect();

        if ($assignments->isNotEmpty()) {
            $requestedId = (int) $request->query('assignment_id', 0);
            $selectedAssignment = $assignments->firstWhere('id', $requestedId) ?? $assignments->first();

            $students = User::query()
                ->where('role', 'siswa_wali')
                ->where('classroom_id', $selectedAssignment->classroom_id)
                ->orderBy('name')
                ->get();

            if ($students->isNotEmpty()) {
                $gradesByStudent = Grade::query()
                    ->where('assignment_id', $selectedAssignment->id)
                    ->whereIn('student_id', $students->pluck('id'))
                    ->get()
                    ->keyBy('student_id');
            }
        }

        $viewMap = [
            'dashboard' => 'guru_mapel.pages.dashboard',
            'input-nilai' => 'guru_mapel.pages.input-nilai',
            'rekap-penugasan' => 'guru_mapel.pages.rekap-penugasan',
        ];

        return view($viewMap[$section], [
            'activeSection' => $section,
            'activeYear' => $activeYear,
            'assignments' => $assignments,
            'selectedAssignment' => $selectedAssignment,
            'students' => $students,
            'gradesByStudent' => $gradesByStudent,
        ]);
    }
}
