<?php

namespace App\Http\Controllers;

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

class AdminDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $allowedPages = ['dashboard', 'tahun-ajaran', 'data-master', 'pengguna-penugasan'];
        $activePage = $request->query('page', 'dashboard');

        if (! in_array($activePage, $allowedPages, true)) {
            $activePage = 'dashboard';
        }

        $academicYears = AcademicYear::query()
            ->orderByDesc('is_active')
            ->orderByDesc('id')
            ->get();

        $activeYear = $academicYears->firstWhere('is_active', true);
        $classrooms = Classroom::query()->orderBy('name')->get();
        $subjects = Subject::query()->orderBy('name')->get();
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

        $viewMap = [
            'dashboard' => 'admin_akademik.pages.dashboard',
            'tahun-ajaran' => 'admin_akademik.pages.tahun-ajaran',
            'data-master' => 'admin_akademik.pages.data-master',
            'pengguna-penugasan' => 'admin_akademik.pages.pengguna-penugasan',
        ];

        return view($viewMap[$activePage], [
            'activePage' => $activePage,
            'activeSection' => $activePage,
            'academicYears' => $academicYears,
            'activeYear' => $activeYear,
            'classrooms' => $classrooms,
            'subjects' => $subjects,
            'teachers' => $teachers,
            'students' => $students,
            'assignments' => $assignments,
        ]);
    }

    public function storeAcademicYear(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:30'],
            'semester' => ['required', Rule::in(['Ganjil', 'Genap'])],
            'is_active' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $isActive = $request->boolean('is_active');
        $isPublished = $request->boolean('is_published');

        DB::transaction(function () use ($validated, $isActive, $isPublished): void {
            if ($isActive) {
                AcademicYear::query()->update(['is_active' => false]);
            }

            AcademicYear::create([
                'name' => trim($validated['name']),
                'semester' => $validated['semester'],
                'is_active' => $isActive,
                'is_published' => $isPublished,
            ]);
        });

        return redirect()
            ->route('admin.dashboard', ['page' => 'tahun-ajaran'])
            ->with('success', 'Data tahun ajaran berhasil disimpan.');
    }

    public function togglePublish(AcademicYear $academicYear): RedirectResponse
    {
        $academicYear->update([
            'is_published' => ! $academicYear->is_published,
        ]);

        $statusText = $academicYear->is_published ? 'dibuka/dipublikasikan' : 'dikunci';

        return redirect()
            ->route('admin.dashboard', ['page' => 'tahun-ajaran'])
            ->with('success', "Publikasi rapor periode {$academicYear->name} berhasil {$statusText}.");
    }

    public function storeClassroom(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:60', 'unique:classrooms,name'],
            'level' => ['required', 'string', 'max:20'],
        ]);

        Classroom::create([
            'name' => trim($validated['name']),
            'level' => trim($validated['level']),
        ]);

        return redirect()
            ->route('admin.dashboard', ['page' => 'data-master'])
            ->with('success', 'Data kelas berhasil ditambahkan.');
    }

    public function storeSubject(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:subjects,code'],
            'name' => ['required', 'string', 'max:100'],
        ]);

        Subject::create([
            'code' => strtoupper(trim($validated['code'])),
            'name' => trim($validated['name']),
        ]);

        return redirect()
            ->route('admin.dashboard', ['page' => 'data-master'])
            ->with('success', 'Data mata pelajaran berhasil ditambahkan.');
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
