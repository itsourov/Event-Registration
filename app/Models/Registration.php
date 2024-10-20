<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'student_id',
        'phone',
        'section',
        'department',
        'lab_teacher_name',
        'tshirt_size',
        'gender',
        'extra',
    ];

    protected function casts(): array
    {
        return [
            'extra' => 'array',
        ];
    }

    /**
     * Get the user that owns the phone.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
