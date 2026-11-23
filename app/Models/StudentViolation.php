<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentViolation extends Model
{
    protected $table = 'student_violations';

    protected $fillable = [
        'student_id',
        'violation_type_id',
        'violation_date',
        'violation_time',
        'points',
        'reporter_id',
        'notes',
        'proof_image',
        'status', // menunggu_tindakan, dalam_pembinaan, selesai
        'follow_up_action',
        'handled_by',
        'handled_at',
    ];

    protected function casts(): array
    {
        return [
            'violation_date' => 'date',
            'handled_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function violationType(): BelongsTo
    {
        return $this->belongsTo(ViolationType::class, 'violation_type_id');
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function handledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}
