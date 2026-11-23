<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\MonthlyAttendance;
use App\Models\ParentSummons;
use App\Models\SchoolClass;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Models\StudentViolation;
use App\Models\ViolationCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isSiswaOrtu()) {
            return redirect()->route('portal.index');
        }

        $school = SchoolSetting::getSettings();
        $today = Carbon::today()->format('Y-m-d');
        $thisMonth = Carbon::now()->month;
        $thisYear = Carbon::now()->year;

        // General Counts
        $totalStudents = Student::where('status', 'aktif')->count();
        $totalClasses = SchoolClass::count();

        // Monthly Attendance Stats
        $monthlyRecap = MonthlyAttendance::where('month', $thisMonth)
            ->where('year', $thisYear)
            ->get();

        if ($monthlyRecap->isNotEmpty()) {
            $totalRecordedMonth = $monthlyRecap->count();
            $effectiveDays = $monthlyRecap->first()->effective_days ?: 22;
            $monthHadir = (int) $monthlyRecap->sum('hadir');
            $monthSakit = (int) $monthlyRecap->sum('sakit');
            $monthIzin = (int) $monthlyRecap->sum('izin');
            $monthAlpa = (int) $monthlyRecap->sum('alpa');
            $totalPossibleDays = $totalRecordedMonth * $effectiveDays;
            $attendanceRateThisMonth = $totalPossibleDays > 0 ? round(($monthHadir / $totalPossibleDays) * 100, 1) : 0;
        } else {
            $monthHadir = 0; $monthSakit = 0; $monthIzin = 0; $monthAlpa = 0; $attendanceRateThisMonth = 0;
        }

        // Keep daily fallback variables for view compatibility
        $todayAttendance = AttendanceRecord::where('date', $today)->get();
        $totalRecordedToday = $todayAttendance->count();
        $todayHadir = $todayAttendance->where('status', 'hadir')->count();
        $todayTerlambat = $todayAttendance->where('status', 'terlambat')->count();
        $todaySakit = $todayAttendance->where('status', 'sakit')->count();
        $todayIzin = $todayAttendance->where('status', 'izin')->count();
        $todayAlpa = $todayAttendance->where('status', 'alpa')->count();
        $attendanceRateToday = $totalRecordedToday > 0 
            ? round((($todayHadir + ($todayTerlambat * 0.5)) / $totalRecordedToday) * 100, 1) 
            : $attendanceRateThisMonth;

        // Violations this month
        $monthlyViolationsCount = StudentViolation::whereMonth('violation_date', $thisMonth)
            ->whereYear('violation_date', $thisYear)
            ->count();
        $monthlyPoints = StudentViolation::whereMonth('violation_date', $thisMonth)
            ->whereYear('violation_date', $thisYear)
            ->sum('points');

        // Summons counts
        $pendingSummonsCount = ParentSummons::where('status', 'diterbitkan')->count();

        // Students needing attention (Highest Net Points)
        $students = Student::with(['class', 'violations', 'pointRestorations'])->get();
        $studentsWithPoints = $students->map(function ($s) {
            return [
                'id' => $s->id,
                'name' => $s->name,
                'nisn' => $s->nisn,
                'class' => $s->class?->name ?? '-',
                'parent_phone' => $s->parent_phone,
                'net_points' => $s->net_points,
                'current_sanction' => $s->current_sanction?->action_name ?? 'Belum ada sanksi',
            ];
        })->sortByDesc('net_points')->values()->take(5);

        // Recent Violations
        $recentViolations = StudentViolation::with(['student.class', 'violationType', 'reporter'])
            ->latest('violation_date')
            ->latest('id')
            ->take(5)
            ->get();

        // 7-day Attendance Trend Chart Data
        $chartLabels = [];
        $chartHadir = [];
        $chartAbsen = []; // Sakit + Izin + Alpa

        for ($i = 6; $i >= 0; $i--) {
            $dateObj = Carbon::now()->subDays($i);
            $dStr = $dateObj->format('Y-m-d');
            $chartLabels[] = $dateObj->translatedFormat('D, d M');

            $records = AttendanceRecord::where('date', $dStr)->get();
            $h = $records->whereIn('status', ['hadir', 'terlambat'])->count();
            $a = $records->whereIn('status', ['sakit', 'izin', 'alpa'])->count();

            $chartHadir[] = $h;
            $chartAbsen[] = $a;
        }

        // Violations by Category
        $categories = ViolationCategory::withCount('violationTypes')->get();
        $catLabels = [];
        $catViolationsCount = [];
        foreach ($categories as $cat) {
            $catLabels[] = $cat->name;
            $count = StudentViolation::whereHas('violationType', function ($q) use ($cat) {
                $q->where('category_id', $cat->id);
            })->count();
            $catViolationsCount[] = $count;
        }

        return view('dashboard.index', compact(
            'school',
            'totalStudents',
            'totalClasses',
            'totalRecordedToday',
            'todayHadir',
            'todayTerlambat',
            'todaySakit',
            'todayIzin',
            'todayAlpa',
            'attendanceRateToday',
            'monthlyViolationsCount',
            'monthlyPoints',
            'pendingSummonsCount',
            'studentsWithPoints',
            'recentViolations',
            'chartLabels',
            'chartHadir',
            'chartAbsen',
            'catLabels',
            'catViolationsCount'
        ));
    }
}
