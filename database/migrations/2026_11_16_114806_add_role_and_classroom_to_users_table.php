<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin_akademik', 'guru_mapel', 'siswa_wali'])
                ->default('siswa_wali')
                ->after('password');

            $table->foreignId('classroom_id')
                ->nullable()
                ->after('role')
                ->constrained('classrooms')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
            $table->dropColumn(['role', 'classroom_id']);
        });
    }
};
