<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            AcademicYear::query()->update(['is_active' => false]);

            $academicYear = AcademicYear::updateOrCreate(
                [
                    'name' => '2026/2027',
                    'semester' => 'Ganjil',
                ],
                [
                    'is_active' => true,
                    'is_published' => false,
                ]
            );

            $classroom = Classroom::updateOrCreate(
                ['name' => 'X RPL 1'],
                ['level' => 'X']
            );

            $subject = Subject::updateOrCreate(
                ['code' => 'PWB'],
                ['name' => 'Pemrograman Web']
            );

            $admin = User::updateOrCreate(
                ['email' => 'admin@sekolah.com'],
                [
                    'name' => 'Admin Akademik',
                    'password' => Hash::make('AdminRapor'),
                    'role' => 'admin_akademik',
                    'classroom_id' => null,
                ]
            );

            $teacher = User::updateOrCreate(
                ['email' => 'guru@sekolah.com'],
                [
                    'name' => 'Guru Mata Pelajaran',
                    'password' => Hash::make('GuruRapor'),
                    'role' => 'guru_mapel',
                    'classroom_id' => null,
                ]
            );

            $student = User::updateOrCreate(
                ['email' => 'siswa@sekolah.com'],
                [
                    'name' => 'Siswa Contoh',
                    'password' => Hash::make('SiswaRapor'),
                    'role' => 'siswa_wali',
                    'classroom_id' => $classroom->id,
                ]
            );

            if (
                ! $admin->id ||
                ! $teacher->id ||
                ! $student->id ||
                ! $classroom->id ||
                ! $subject->id ||
                ! $academicYear->id
            ) {
                throw new RuntimeException('Gagal menyiapkan data master atau akun pengguna awal.');
            }

            $assignment = TeachingAssignment::updateOrCreate(
                [
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subject->id,
                    'classroom_id' => $classroom->id,
                    'academic_year_id' => $academicYear->id,
                ],
                []
            );

            $tugas = 85.00;
            $uts = 80.00;
            $uas = 88.00;
            $nilaiAkhir = round(($tugas * 0.30) + ($uts * 0.30) + ($uas * 0.40), 2);
            $predikat = match (true) {
                $nilaiAkhir >= 90 => 'A',
                $nilaiAkhir >= 80 => 'B',
                $nilaiAkhir >= 70 => 'C',
                default => 'D',
            };

            Grade::updateOrCreate(
                [
                    'assignment_id' => $assignment->id,
                    'student_id' => $student->id,
                ],
                [
                    'tugas' => $tugas,
                    'uts' => $uts,
                    'uas' => $uas,
                    'nilai_akhir' => $nilaiAkhir,
                    'predikat' => $predikat,
                    'deskripsi' => trim('Menguasai materi dasar dan cukup baik dalam penerapan praktik.'),
                    'is_finalized' => false,
                ]
            );
        });
    }
}
