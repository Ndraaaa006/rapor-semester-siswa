<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')
                ->constrained('teaching_assignments')
                ->cascadeOnDelete();
            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->decimal('tugas', 5, 2)->default(0);
            $table->decimal('uts', 5, 2)->default(0);
            $table->decimal('uas', 5, 2)->default(0);
            $table->decimal('nilai_akhir', 5, 2)->default(0);
            $table->string('predikat', 5)->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_finalized')->default(false);

            $table->timestamps();

            $table->unique(['assignment_id', 'student_id'], 'grade_assignment_student_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
