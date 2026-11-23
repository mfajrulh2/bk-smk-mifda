<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ViolationCategory extends Model
{
    protected $table = 'violation_categories';

    protected $fillable = [
        'name',
        'description',
    ];

    public function violationTypes(): HasMany
    {
        return $this->hasMany(ViolationType::class, 'category_id');
    }
}
