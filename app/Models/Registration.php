<?php

namespace App\Models;

use App\Enums\RegistrationStatuses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Registration extends Model
{
    use SoftDeletes;

    use LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

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
