<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PenggunaPenugasanController extends Controller
{
    public function index(): View
    {
        $academicYears = AcademicYear::query()
            ->orderByDesc('is_active')
            ->orderByDesc('id')
            ->get();

        $activeYear = $academicYears->firstWhere('is_active', true);
        $allClassrooms = Classroom::query()->orderBy('name')->get();
        $allSubjects = Subject::query()->orderBy('name')->get();
        $teachers = User::query()->where('role', 'guru_mapel')->orderBy('name')->get();
        $students = User::query()
            ->with('classroom')
            ->where('role', 'siswa_wali')
            ->orderBy('name')
            ->get();

        $assignments = TeachingAssignment::query()
            ->with(['teacher', 'subject', 'classroom', 'academicYear'])
            ->orderByDesc('id')
            ->get();

        return view('admin_akademik.pages.pengguna-penugasan', [
            'activePage' => 'pengguna-penugasan',
            'activeSection' => 'pengguna-penugasan',
            'academicYears' => $academicYears,
            'activeYear' => $activeYear,
            'allClassrooms' => $allClassrooms,
            'allSubjects' => $allSubjects,
            'classrooms' => $allClassrooms,
            'subjects' => $allSubjects,
            'teachers' => $teachers,
            'students' => $students,
            'assignments' => $assignments,
        ]);
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:100'],
            'role' => ['required', Rule::in(['guru_mapel', 'siswa_wali'])],
            'classroom_id' => ['nullable', 'integer', 'exists:classrooms,id'],
        ]);

        $classroomId = $validated['role'] === 'siswa_wali'
            ? ($validated['classroom_id'] ?? null)
            : null;

        User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'classroom_id' => $classroomId,
        ]);

        return redirect()
            ->route('admin.dashboard', ['page' => 'pengguna-penugasan'])
            ->with('success', 'Akun pengguna baru berhasil didaftarkan.');
    }

    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        if ($request->user() && $user->id === $request->user()->id) {
            return redirect()
                ->route('admin.dashboard', ['page' => 'pengguna-penugasan'])
                ->with('error', 'Akun yang sedang aktif digunakan tidak dapat dihapus.');
        }

        if ($user->role === 'admin_akademik') {
            return redirect()
                ->route('admin.dashboard', ['page' => 'pengguna-penugasan'])
                ->with('error', 'Akun Admin Akademik tidak dapat dihapus dari halaman ini.');
        }

        $hasTeacherAssignment = TeachingAssignment::query()
            ->where('teacher_id', $user->id)
            ->exists();

        $hasStudentGrade = Grade::query()
            ->where('student_id', $user->id)
            ->exists();

        if ($hasTeacherAssignment || $hasStudentGrade) {
            return redirect()
                ->route('admin.dashboard', ['page' => 'pengguna-penugasan'])
                ->with('error', 'Akun tidak dapat dihapus karena masih memiliki penugasan mengajar atau data nilai.');
        }

        DB::transaction(function () use ($user): void {
            $user->delete();
        });

        return redirect()
            ->route('admin.dashboard', ['page' => 'pengguna-penugasan'])
            ->with('success', 'Akun pengguna berhasil dihapus.');
    }

    public function storeAssignment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'teacher_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(fn($q) => $q->where('role', 'guru_mapel')),
            ],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'classroom_id' => ['required', 'integer', 'exists:classrooms,id'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
        ]);

        TeachingAssignment::firstOrCreate([
            'teacher_id' => (int) $validated['teacher_id'],
            'subject_id' => (int) $validated['subject_id'],
            'classroom_id' => (int) $validated['classroom_id'],
            'academic_year_id' => (int) $validated['academic_year_id'],
        ]);

        return redirect()
            ->route('admin.dashboard', ['page' => 'pengguna-penugasan'])
            ->with('success', 'Penugasan mengajar berhasil disimpan.');
    }

    public function destroyAssignment(TeachingAssignment $assignment): RedirectResponse
    {
        $hasGrades = Grade::query()
            ->where('assignment_id', $assignment->id)
            ->exists();

        if ($hasGrades) {
            return redirect()
                ->route('admin.dashboard', ['page' => 'pengguna-penugasan'])
                ->with('error', 'Penugasan mengajar tidak dapat dihapus karena sudah memiliki data nilai.');
        }

        DB::transaction(function () use ($assignment): void {
            $assignment->delete();
        });

        return redirect()
            ->route('admin.dashboard', ['page' => 'pengguna-penugasan'])
            ->with('success', 'Penugasan mengajar berhasil dihapus.');
    }
}
