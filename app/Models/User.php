<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role', // admin, guru, bk, siswa_ortu
        'phone',
        'identifier_number',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isBK(): bool
    {
        return $this->role === 'bk';
    }

    public function isSiswaOrtu(): bool
    {
        return $this->role === 'siswa_ortu';
    }

    public function homeroomClasses(): HasMany
    {
        return $this->hasMany(SchoolClass::class, 'homeroom_teacher_id');
    }

    public function reportedViolations(): HasMany
    {
        return $this->hasMany(StudentViolation::class, 'reporter_id');
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class, 'user_id');
    }
}
