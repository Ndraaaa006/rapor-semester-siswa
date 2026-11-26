<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50); // Contoh: 2026/2027
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->boolean('is_active')->default(false); // Menandai tahun ajaran & semester aktif
            $table->boolean('is_published')->default(false); // Buka/kunci publikasi rapor semester oleh Admin Akademik
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
