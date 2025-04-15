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
    ];

    protected function casts(): array
    {
        return [
            'courses' => 'array',
        ];
    }
}
