<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RaporController extends Controller
{
    public function index(Request $request): View
    {
        return $this->renderSection($request, 'rapor-semester');
    }

    public function renderSection(Request $request, string $section): View
    {
        $student = $request->user()->load('classroom');

        $allowedSections = ['dashboard', 'rapor-semester'];
        if (! in_array($section, $allowedSections, true)) {
            $section = 'rapor-semester';
        }

        $activeYear = AcademicYear::query()
            ->where('is_active', true)
            ->first();

        $isPublished = (bool) optional($activeYear)->is_published;
        $grades = collect();
        $averageScore = null;

        if ($activeYear && $isPublished) {
            // Batasi query hanya pada student_id milik user yang sedang login (anti IDOR)
            $grades = Grade::query()
                ->with(['assignment.subject', 'assignment.teacher'])
                ->where('student_id', $student->id)
                ->whereHas('assignment', function ($query) use ($activeYear) {
                    $query->where('academic_year_id', $activeYear->id);
                })
                ->whereNotNull('nilai_akhir')
                ->get();

            if ($grades->isNotEmpty()) {
                $averageScore = round((float) $grades->avg('nilai_akhir'), 2);
            }
        }

        $viewName = $section === 'dashboard'
            ? 'siswa_wali.pages.dashboard'
            : 'siswa_wali.pages.rapor-semester';

        return view($viewName, [
            'activeSection' => $section,
            'student' => $student,
            'activeYear' => $activeYear,
            'isPublished' => $isPublished,
            'grades' => $grades,
            'averageScore' => $averageScore,
        ]);
    }
}
