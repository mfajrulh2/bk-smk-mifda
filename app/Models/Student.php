<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'nisn',
        'nis',
        'name',
        'gender',
        'class_id',
        'phone',
        'parent_name',
        'parent_phone',
        'address',
        'photo',
        'user_id',
        'status',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function violations(): HasMany
    {
        return $this->hasMany(StudentViolation::class, 'student_id');
    }

    public function pointRestorations(): HasMany
    {
        return $this->hasMany(PointRestoration::class, 'student_id');
    }

    public function summons(): HasMany
    {
        return $this->hasMany(ParentSummons::class, 'student_id');
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class, 'student_id');
    }

    public function monthlyAttendances(): HasMany
    {
        return $this->hasMany(MonthlyAttendance::class, 'student_id');
    }

    public function getTotalViolationPointsAttribute(): int
    {
        return (int) $this->violations()->sum('points');
    }

    public function getTotalRestoredPointsAttribute(): int
    {
        return (int) $this->pointRestorations()->sum('restored_points');
    }

    public function getNetPointsAttribute(): int
    {
        $net = $this->total_violation_points - $this->total_restored_points;
        return max(0, $net);
    }

    public function getCurrentSanctionAttribute(): ?SanctionThreshold
    {
        $points = $this->net_points;
        return SanctionThreshold::where('min_points', '<=', $points)
            ->orderBy('min_points', 'desc')
            ->first();
    }

    public function getAttendanceStatsAttribute(): array
    {
        $monthly = $this->monthlyAttendances;
        if ($monthly->isNotEmpty()) {
            $totalDays = (int) $monthly->sum('effective_days');
            $hadir = (int) $monthly->sum('hadir');
            $sakit = (int) $monthly->sum('sakit');
            $izin = (int) $monthly->sum('izin');
            $alpa = (int) $monthly->sum('alpa');

            $percentage = $totalDays > 0 ? round(($hadir / $totalDays) * 100, 1) : 100;

            return [
                'total' => $totalDays,
                'hadir' => $hadir,
                'terlambat' => 0,
                'sakit' => $sakit,
                'izin' => $izin,
                'alpa' => $alpa,
                'percentage' => $percentage,
            ];
        }

        $records = $this->attendanceRecords;
        $total = $records->count();
        $hadir = $records->where('status', 'hadir')->count();
        $terlambat = $records->where('status', 'terlambat')->count();
        $sakit = $records->where('status', 'sakit')->count();
        $izin = $records->where('status', 'izin')->count();
        $alpa = $records->where('status', 'alpa')->count();

        $percentage = $total > 0 ? round((($hadir + ($terlambat * 0.5)) / $total) * 100, 1) : 100;

        return [
            'total' => $total,
            'hadir' => $hadir,
            'terlambat' => $terlambat,
            'sakit' => $sakit,
            'izin' => $izin,
            'alpa' => $alpa,
            'percentage' => $percentage,
        ];
    }
}
