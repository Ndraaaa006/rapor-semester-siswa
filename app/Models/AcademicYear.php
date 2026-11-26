<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    use HasFactory;

    protected $table = 'academic_years';

    protected $fillable = [
        'name',
        'semester',
        'is_active',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function teachingAssignments(): HasMany
    {
        return $this->hasMany(TeachingAssignment::class, 'academic_year_id');
    }
}
