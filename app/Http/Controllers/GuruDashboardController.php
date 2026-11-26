<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GuruDashboardController extends Controller
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

    public function saveGrades(Request $request, TeachingAssignment $assignment): RedirectResponse
    {
        if ((int) $assignment->teacher_id !== (int) $request->user()?->id) {
            abort(403, 'Anda tidak berhak mengelola nilai pada penugasan ini.');
        }

        $validated = $request->validate([
            'grades' => ['required', 'array'],
            'grades.*.tugas' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.uts' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.uas' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.deskripsi' => ['nullable', 'string', 'max:255'],
            'grades.*.is_finalized' => ['nullable', 'boolean'],
        ]);

        $validStudentIds = User::query()
            ->where('role', 'siswa_wali')
            ->where('classroom_id', $assignment->classroom_id)
            ->pluck('id')
            ->flip();

        $finalizedCount = 0;
        $skippedLocked = 0;

        DB::transaction(function () use (
            $validated,
            $assignment,
            $validStudentIds,
            &$finalizedCount,
            &$skippedLocked
        ): void {
            foreach ($validated['grades'] as $studentId => $row) {
                $studentId = (int) $studentId;

                if (! $validStudentIds->has($studentId)) {
                    continue;
                }

                $existing = Grade::query()
                    ->where('assignment_id', $assignment->id)
                    ->where('student_id', $studentId)
                    ->first();

                if ($existing?->is_finalized) {
                    $skippedLocked++;
                    continue;
                }

                $tugas = isset($row['tugas']) && $row['tugas'] !== '' ? (float) $row['tugas'] : null;
                $uts = isset($row['uts']) && $row['uts'] !== '' ? (float) $row['uts'] : null;
                $uas = isset($row['uas']) && $row['uas'] !== '' ? (float) $row['uas'] : null;

                $nilaiAkhir = null;
                $predikat = null;

                if ($tugas !== null && $uts !== null && $uas !== null) {
                    // Bobot standar: Tugas 30%, UTS 30%, UAS 40%
                    $nilaiAkhir = round(($tugas * 0.30) + ($uts * 0.30) + ($uas * 0.40), 2);
                    $predikat = $this->calculatePredicate($nilaiAkhir);
                }

                $deskripsi = isset($row['deskripsi']) ? trim((string) $row['deskripsi']) : null;
                $deskripsi = $deskripsi === '' ? null : $deskripsi;

                $rowFinalized = filter_var($row['is_finalized'] ?? false, FILTER_VALIDATE_BOOLEAN);
                if ($rowFinalized) {
                    $finalizedCount++;
                }

                Grade::updateOrCreate(
                    [
                        'assignment_id' => $assignment->id,
                        'student_id' => $studentId,
                    ],
                    [
                        'tugas' => $tugas,
                        'uts' => $uts,
                        'uas' => $uas,
                        'nilai_akhir' => $nilaiAkhir,
                        'predikat' => $predikat,
                        'deskripsi' => $deskripsi,
                        'is_finalized' => $rowFinalized,
                    ]
                );
            }
        });

        $message = $finalizedCount > 0
            ? 'Nilai berhasil disimpan dan difinalisasi.'
            : 'Draft nilai berhasil disimpan.';

        if ($skippedLocked > 0) {
            $message .= " ({$skippedLocked} data yang sudah final tidak diubah.)";
        }

        return redirect()
            ->route('guru.dashboard', [
                'section' => 'input-nilai',
                'assignment_id' => $assignment->id,
            ])
            ->with('success', $message);
    }

    private function calculatePredicate(float $score): string
    {
        return match (true) {
            $score >= 90 => 'A',
            $score >= 80 => 'B',
            $score >= 70 => 'C',
            default => 'D',
        };
    }
}
