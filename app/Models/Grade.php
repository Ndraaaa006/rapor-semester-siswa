<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory;

    protected $table = 'grades';

    protected $fillable = [
        'assignment_id',
        'student_id',
        'tugas',
        'uts',
        'uas',
        'nilai_akhir',
        'predikat',
        'deskripsi',
        'is_finalized',
    ];

    protected function casts(): array
    {
        return [
            'tugas' => 'decimal:2',
            'uts' => 'decimal:2',
            'uas' => 'decimal:2',
            'nilai_akhir' => 'decimal:2',
            'is_finalized' => 'boolean',
        ];
    }

    public function assignment()
    {
        return $this->belongsTo(TeachingAssignment::class, 'assignment_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
