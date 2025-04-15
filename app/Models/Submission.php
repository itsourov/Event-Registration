<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'name',
        'student_id',
        'email',
        'phone',
        'semester',
        'courses',
        'batch',
        'tracker_url',
        'total_solved',
    ];

    protected function casts(): array
    {
        return [
            'courses' => 'array',
        ];
    }
}
