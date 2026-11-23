<?php

namespace App\Http\Controllers;

use App\Models\MonthlyAttendance;
use App\Models\SchoolClass;
use App\Models\SchoolSetting;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $month = (int) $request->input('month', Carbon::now()->month);
        $year = (int) $request->input('year', Carbon::now()->year);

        $classes = SchoolClass::withCount('students')->get();

        $statsPerClass = [];
        foreach ($classes as $c) {
            $records = MonthlyAttendance::where('class_id', $c->id)
                ->where('month', $month)
                ->where('year', $year)
                ->get();

            $totalRecorded = $records->count();
            $effectiveDays = $records->first()?->effective_days ?? 22;
            $totalHadir = (int) $records->sum('hadir');
            $totalSakit = (int) $records->sum('sakit');
            $totalIzin = (int) $records->sum('izin');
            $totalAlpa = (int) $records->sum('alpa');

            $totalPossibleDays = $totalRecorded * $effectiveDays;
            $percentage = $totalPossibleDays > 0 ? round(($totalHadir / $totalPossibleDays) * 100, 1) : 0;

            $statsPerClass[$c->id] = [
                'recorded' => $totalRecorded > 0,
                'total_students' => $c->students_count,
                'total_recorded' => $totalRecorded,
                'effective_days' => $effectiveDays,
                'hadir' => $totalHadir,
                'sakit' => $totalSakit,
                'izin' => $totalIzin,
                'alpa' => $totalAlpa,
                'percentage' => $percentage,
            ];
        }

        return view('attendance.index', compact('classes', 'month', 'year', 'statsPerClass'));
    }

    public function input(Request $request)
    {
        $classes = SchoolClass::all();
        $classId = $request->input('class_id', $classes->first()?->id);
        $month = (int) $request->input('month', Carbon::now()->month);
        $year = (int) $request->input('year', Carbon::now()->year);

        $selectedClass = $classId ? SchoolClass::with('students')->find($classId) : $classes->first();

        if ($selectedClass) {
            $students = Student::where('class_id', $selectedClass->id)
                ->where('status', 'aktif')
                ->orderBy('name')
                ->get();

            $existingRecords = MonthlyAttendance::where('class_id', $selectedClass->id)
                ->where('month', $month)
                ->where('year', $year)
                ->get()
                ->keyBy('student_id');

            $effectiveDays = $existingRecords->first()?->effective_days ?? 22;
        } else {
            $students = collect();
            $existingRecords = collect();
            $effectiveDays = 22;
        }

        return view('attendance.input', compact('classes', 'selectedClass', 'month', 'year', 'effectiveDays', 'students', 'existingRecords'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2035',
            'effective_days' => 'required|integer|min:1|max:31',
            'recap' => 'required|array',
            'recap.*.sakit' => 'required|integer|min:0|max:31',
            'recap.*.izin' => 'required|integer|min:0|max:31',
            'recap.*.alpa' => 'required|integer|min:0|max:31',
            'recap.*.notes' => 'nullable|string|max:255',
        ]);

        $classId = (int) $request->input('class_id');
        $month = (int) $request->input('month');
        $year = (int) $request->input('year');
        $effectiveDays = (int) $request->input('effective_days');
        $recaps = $request->input('recap');
        $userId = Auth::id();

        $selectedClass = SchoolClass::findOrFail($classId);
        $monthName = Carbon::createFromDate($year, $month, 1)->translatedFormat('F');

        foreach ($recaps as $studentId => $data) {
            $sakit = (int) ($data['sakit'] ?? 0);
            $izin = (int) ($data['izin'] ?? 0);
            $alpa = (int) ($data['alpa'] ?? 0);

            // Hitung otomatis hadir = total hari efektif dikurangi ketidakhadiran (S+I+A)
            $totalTidakHadir = $sakit + $izin + $alpa;
            $hadir = max(0, $effectiveDays - $totalTidakHadir);

            MonthlyAttendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'month' => $month,
                    'year' => $year,
                ],
                [
                    'class_id' => $classId,
                    'effective_days' => $effectiveDays,
                    'hadir' => $hadir,
                    'sakit' => $sakit,
                    'izin' => $izin,
                    'alpa' => $alpa,
                    'notes' => $data['notes'] ?? null,
                    'recorded_by' => $userId,
                ]
            );
        }

        return redirect()->route('attendance.input', [
            'class_id' => $classId,
            'month' => $month,
            'year' => $year,
        ])->with('success', "Rekapitulasi presensi kelas {$selectedClass->name} untuk bulan {$monthName} {$year} berhasil disimpan!");
    }

    public function recap(Request $request)
    {
        $classes = SchoolClass::all();
        $classId = $request->input('class_id', $classes->first()?->id);
        $month = (int) $request->input('month', Carbon::now()->month);
        $year = (int) $request->input('year', Carbon::now()->year);

        $selectedClass = SchoolClass::find($classId);

        if (!$selectedClass) {
            return view('attendance.recap', compact('classes', 'selectedClass'));
        }

        $students = Student::where('class_id', $selectedClass->id)
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get();

        $existingRecords = MonthlyAttendance::where('class_id', $selectedClass->id)
            ->where('month', $month)
            ->where('year', $year)
            ->get()
            ->keyBy('student_id');

        $effectiveDays = $existingRecords->first()?->effective_days ?? 22;

        $recapData = [];
        foreach ($students as $student) {
            $rec = $existingRecords->get($student->id);

            $eff = $rec ? $rec->effective_days : $effectiveDays;
            $sakit = $rec ? $rec->sakit : 0;
            $izin = $rec ? $rec->izin : 0;
            $alpa = $rec ? $rec->alpa : 0;
            $hadir = $rec ? $rec->hadir : $eff;
            $notes = $rec ? $rec->notes : '';

            $pct = $eff > 0 ? round(($hadir / $eff) * 100, 1) : 100.0;

            $recapData[] = [
                'student' => $student,
                'effective_days' => $eff,
                'hadir' => $hadir,
                'sakit' => $sakit,
                'izin' => $izin,
                'alpa' => $alpa,
                'percentage' => $pct,
                'notes' => $notes,
            ];
        }

        return view('attendance.recap', compact('classes', 'selectedClass', 'month', 'year', 'effectiveDays', 'recapData'));
    }

    public function exportExcel(Request $request)
    {
        $classId = $request->input('class_id');
        $month = (int) $request->input('month', Carbon::now()->month);
        $year = (int) $request->input('year', Carbon::now()->year);

        $class = SchoolClass::findOrFail($classId);
        $school = SchoolSetting::getSettings();
        $monthName = Carbon::createFromDate($year, $month, 1)->translatedFormat('F');

        $students = Student::where('class_id', $class->id)->where('status', 'aktif')->orderBy('name')->get();
        $records = MonthlyAttendance::where('class_id', $class->id)
            ->where('month', $month)
            ->where('year', $year)
            ->get()
            ->keyBy('student_id');

        $effectiveDays = $records->first()?->effective_days ?? 22;
        $filename = "Rekap_Presensi_Bulanan_{$class->name}_{$monthName}_{$year}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($students, $records, $class, $monthName, $year, $school, $effectiveDays) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [$school->school_name]);
            fputcsv($handle, ["REKAPITULASI PRESENSI BULANAN SISWA KELAS {$class->name}"]);
            fputcsv($handle, ["Bulan: {$monthName} {$year}"]);
            fputcsv($handle, ["Hari Efektif Belajar: {$effectiveDays} Hari"]);
            fputcsv($handle, []);

            // Column Header
            fputcsv($handle, ['No', 'NISN', 'NIS', 'Nama Siswa', 'L/P', 'Hari Efektif', 'Hadir', 'Sakit (S)', 'Izin (I)', 'Alpa (A)', '% Kehadiran', 'Keterangan']);

            $no = 1;
            foreach ($students as $student) {
                $rec = $records->get($student->id);
                $eff = $rec ? $rec->effective_days : $effectiveDays;
                $sakit = $rec ? $rec->sakit : 0;
                $izin = $rec ? $rec->izin : 0;
                $alpa = $rec ? $rec->alpa : 0;
                $hadir = $rec ? $rec->hadir : $eff;
                $notes = $rec ? $rec->notes : '';
                $pct = $eff > 0 ? round(($hadir / $eff) * 100, 1) . '%' : '100%';

                fputcsv($handle, [
                    $no++,
                    $student->nisn,
                    $student->nis ?? '-',
                    $student->name,
                    $student->gender,
                    $eff,
                    $hadir,
                    $sakit,
                    $izin,
                    $alpa,
                    $pct,
                    $notes,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function printRecap(Request $request)
    {
        $classId = $request->input('class_id');
        $month = (int) $request->input('month', Carbon::now()->month);
        $year = (int) $request->input('year', Carbon::now()->year);

        $selectedClass = SchoolClass::with('homeroomTeacher')->findOrFail($classId);
        $school = SchoolSetting::getSettings();
        $monthName = Carbon::createFromDate($year, $month, 1)->translatedFormat('F');

        $students = Student::where('class_id', $selectedClass->id)->where('status', 'aktif')->orderBy('name')->get();
        $records = MonthlyAttendance::where('class_id', $selectedClass->id)
            ->where('month', $month)
            ->where('year', $year)
            ->get()
            ->keyBy('student_id');

        $effectiveDays = $records->first()?->effective_days ?? 22;

        $recapData = [];
        foreach ($students as $student) {
            $rec = $records->get($student->id);
            $eff = $rec ? $rec->effective_days : $effectiveDays;
            $sakit = $rec ? $rec->sakit : 0;
            $izin = $rec ? $rec->izin : 0;
            $alpa = $rec ? $rec->alpa : 0;
            $hadir = $rec ? $rec->hadir : $eff;
            $notes = $rec ? $rec->notes : '';
            $pct = $eff > 0 ? round(($hadir / $eff) * 100, 1) : 100.0;

            $recapData[] = [
                'student' => $student,
                'effective_days' => $eff,
                'hadir' => $hadir,
                'sakit' => $sakit,
                'izin' => $izin,
                'alpa' => $alpa,
                'percentage' => $pct,
                'notes' => $notes,
            ];
        }

        return view('attendance.print', compact('selectedClass', 'school', 'month', 'year', 'monthName', 'effectiveDays', 'recapData'));
    }
}
