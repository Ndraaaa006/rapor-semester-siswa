<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TahunAjaranController extends Controller
{
    public function index(): View
    {
        $academicYears = AcademicYear::query()
            ->orderByDesc('is_active')
            ->orderByDesc('id')
            ->get();

        $activeYear = $academicYears->firstWhere('is_active', true);

        return view('admin_akademik.pages.tahun-ajaran', [
            'activePage' => 'tahun-ajaran',
            'activeSection' => 'tahun-ajaran',
            'academicYears' => $academicYears,
            'activeYear' => $activeYear,
        ]);
    }

    public function store(Request $request): RedirectResponse
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
}
