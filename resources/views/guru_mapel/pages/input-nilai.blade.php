@extends('layouts.app')

@section('title', 'Input & Finalisasi Nilai')

@section('content')
    @php
        $assignments = $assignments ?? collect();
        $selectedAssignment = $selectedAssignment ?? $assignments->first();
        $students = $students ?? collect();
        $gradesByStudent = $gradesByStudent ?? collect();
    @endphp

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Input &amp; Finalisasi Nilai</h1>
            <p class="text-muted small mb-0">Isi nilai Tugas, UTS, UAS, dan deskripsi kompetensi, lalu finalisasi bila sudah sesuai.</p>
        </div>

        <a href="{{ route('guru.dashboard', ['section' => 'dashboard']) }}" class="btn btn-light border rounded-pill px-3 py-2 small fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="rd-card p-4 mb-4">
        <form method="GET" action="{{ route('guru.dashboard') }}" class="row g-3 align-items-end">
            <input type="hidden" name="section" value="input-nilai">
            <div class="col-12 col-md-8">
                <label for="assignment_id" class="form-label fw-semibold small">Pilih Penugasan Mengajar</label>
                <select id="assignment_id" name="assignment_id" class="form-select">
                    @forelse ($assignments as $item)
                        <option value="{{ $item->id }}" @selected($selectedAssignment?->id === $item->id)>
                            {{ $item->subject?->name ?? 'Mapel' }} — {{ $item->classroom?->name ?? 'Kelas' }} ({{ $item->academicYear?->name ?? '-' }})
                        </option>
                    @empty
                        <option value="">Tidak ada penugasan tersedia</option>
                    @endforelse
                </select>
            </div>
            <div class="col-12 col-md-4">
                <button type="submit" class="btn rd-btn-emerald w-100 py-2 small">
                    <i class="bi bi-funnel me-1"></i> Tampilkan Kelas
                </button>
            </div>
        </form>
    </div>

    @if ($selectedAssignment)
        <div class="rd-card p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <h2 class="h6 fw-bold mb-1">
                        {{ $selectedAssignment->subject?->name }} · Kelas {{ $selectedAssignment->classroom?->name }}
                    </h2>
                    <p class="text-muted small mb-0">Bobot standar: Tugas 30%, UTS 30%, UAS 40%.</p>
                </div>
                <span class="badge rounded-pill text-bg-success-subtle text-success border border-success-subtle px-3 py-2">
                    {{ $students->count() }} Siswa
                </span>
            </div>

            <form method="POST" action="{{ route('guru.grades.save', ['assignment' => $selectedAssignment->id]) }}">
                @csrf

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-3">
                        <thead class="table-light small text-uppercase text-muted">
                            <tr>
                                <th>Nama Siswa</th>
                                <th class="text-center">Tugas</th>
                                <th class="text-center">UTS</th>
                                <th class="text-center">UAS</th>
                                <th class="text-center">Nilai Akhir</th>
                                <th class="text-center">Predikat</th>
                                <th>Deskripsi</th>
                                <th class="text-center">Finalisasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                                @php
                                    $grade = $gradesByStudent->get($student->id);
                                    $isLocked = (bool) ($grade?->is_finalized ?? false);
                                @endphp
                                <tr>
                                    <td class="fw-semibold text-dark">
                                        {{ $student->name }}
                                    </td>
                                    <td style="width: 95px;">
                                        <input type="number" step="0.01" min="0" max="100"
                                               name="grades[{{ $student->id }}][tugas]"
                                               value="{{ old("grades.{$student->id}.tugas", $grade?->tugas) }}"
                                               @disabled($isLocked)
                                               class="form-control form-control-sm text-center">
                                    </td>
                                    <td style="width: 95px;">
                                        <input type="number" step="0.01" min="0" max="100"
                                               name="grades[{{ $student->id }}][uts]"
                                               value="{{ old("grades.{$student->id}.uts", $grade?->uts) }}"
                                               @disabled($isLocked)
                                               class="form-control form-control-sm text-center">
                                    </td>
                                    <td style="width: 95px;">
                                        <input type="number" step="0.01" min="0" max="100"
                                               name="grades[{{ $student->id }}][uas]"
                                               value="{{ old("grades.{$student->id}.uas", $grade?->uas) }}"
                                               @disabled($isLocked)
                                               class="form-control form-control-sm text-center">
                                    </td>
                                    <td class="text-center fw-bold">
                                        {{ $grade?->nilai_akhir !== null ? number_format((float) $grade->nilai_akhir, 2) : '-' }}
                                    </td>
                                    <td class="text-center">
                                        @if ($grade?->predikat)
                                            <x-grade-badge :predikat="$grade->predikat" />
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td style="min-width: 210px;">
                                        <input type="text" maxlength="255"
                                               name="grades[{{ $student->id }}][deskripsi]"
                                               value="{{ old("grades.{$student->id}.deskripsi", $grade?->deskripsi) }}"
                                               @disabled($isLocked)
                                               placeholder="Catatan capaian kompetensi..."
                                               class="form-control form-control-sm">
                                    </td>
                                    <td class="text-center">
                                        @if ($isLocked)
                                            <span class="badge rounded-pill text-bg-success">Final</span>
                                        @else
                                            <input type="checkbox" name="grades[{{ $student->id }}][is_finalized]" value="1"
                                                   @checked(old("grades.{$student->id}.is_finalized", false))
                                                   class="form-check-input" title="Tandai final">
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted small py-4">
                                        Belum ada siswa terdaftar di kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($students->isNotEmpty())
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn rd-btn-emerald px-4 py-2 small">
                            <i class="bi bi-save me-1"></i> Simpan Nilai
                        </button>
                    </div>
                @endif
            </form>
        </div>
    @endif
@endsection
