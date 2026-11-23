<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParentSummons extends Model
{
    protected $table = 'parent_summons';

    protected $fillable = [
        'student_id',
        'letter_number',
        'summons_type',
        'summons_date',
        'summons_time',
        'meeting_place',
        'reason',
        'total_points_snapshot',
        'status', // diterbitkan, hadir, dijadwalkan_ulang, tidak_hadir, selesai
        'meeting_result',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'summons_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
