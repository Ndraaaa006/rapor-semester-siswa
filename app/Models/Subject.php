<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';

    protected $fillable = [
        'code',
        'name',
    ];

    public function teachingAssignments(): HasMany
    {
        return $this->hasMany(TeachingAssignment::class, 'subject_id');
    }
}
