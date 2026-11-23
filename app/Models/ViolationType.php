<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ViolationType extends Model
{
    protected $table = 'violation_types';

    protected $fillable = [
        'category_id',
        'name',
        'points',
        'default_sanction',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ViolationCategory::class, 'category_id');
    }

    public function violations(): HasMany
    {
        return $this->hasMany(StudentViolation::class, 'violation_type_id');
    }
}
