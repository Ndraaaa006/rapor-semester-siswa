<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DataMasterController extends Controller
{
    public function index(): View
    {
        $activeYear = AcademicYear::query()->where('is_active', true)->first();

        $classrooms = Classroom::query()
            ->orderBy('name')
            ->paginate(5, ['*'], 'kelas_page');

        $subjects = Subject::query()
            ->orderBy('name')
            ->paginate(5, ['*'], 'mapel_page');

        return view('admin_akademik.pages.data-master', [
            'activePage' => 'data-master',
            'activeSection' => 'data-master',
            'activeYear' => $activeYear,
            'classrooms' => $classrooms,
            'subjects' => $subjects,
        ]);
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
}
