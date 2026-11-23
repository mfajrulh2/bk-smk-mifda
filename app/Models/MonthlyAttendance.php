<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyAttendance extends Model
{
    protected $table = 'monthly_attendances';

    protected $fillable = [
        'student_id',
        'class_id',
        'month',
        'year',
        'academic_year',
        'effective_days',
        'hadir',
        'sakit',
        'izin',
        'alpa',
        'notes',
        'recorded_by',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function getPercentageAttribute(): float
    {
        if ($this->effective_days <= 0) {
            return 100.0;
        }

        $pct = ($this->hadir / $this->effective_days) * 100;
        return round(max(0, min(100, $pct)), 1);
    }
}
