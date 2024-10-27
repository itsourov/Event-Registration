<?php

namespace App\Models;

use App\Enums\RegistrationStatuses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'contest_id',
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
        'status',
        'transportation_service',
        'pickup_point',
        'payment_method',
        'payment_phone',
        'payment_transaction_id',
    ];

    protected function casts(): array
    {
        return [
            'extra' => 'array',
            'status' => RegistrationStatuses::class,
        ];
    }

    /**
     * Get the user that owns the phone.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get the user that owns the phone.
     */
    public function contest(): BelongsTo
    {
        return $this->belongsTo(Contest::class);
    }
}
