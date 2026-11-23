<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointRestoration extends Model
{
    protected $table = 'point_restorations';

    protected $fillable = [
        'student_id',
        'restored_points',
        'restoration_date',
        'reason',
        'notes',
        'officer_id',
    ];

    protected function casts(): array
    {
        return [
            'restoration_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }
}
