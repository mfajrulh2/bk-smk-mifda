<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanctionThreshold extends Model
{
    protected $table = 'sanction_thresholds';

    protected $fillable = [
        'min_points',
        'action_name',
        'description',
        'requires_summons',
    ];

    protected function casts(): array
    {
        return [
            'requires_summons' => 'boolean',
        ];
    }
}
