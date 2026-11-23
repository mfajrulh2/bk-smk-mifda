<?php

namespace App\Http\Controllers;

use App\Models\ParentSummons;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SummonsController extends Controller
{
    public function index(Request $request)
    {
        $query = ParentSummons::with(['student.class', 'creator']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $summons = $query->latest('summons_date')->latest('id')->paginate(15)->withQueryString();

        // Students with high points who might need summons
        $eligibleStudents = Student::with(['class', 'violations', 'pointRestorations'])
            ->get()
            ->filter(function ($s) {
                return $s->net_points >= 30;
            })
            ->sortByDesc('net_points');

        return view('summons.index', compact('summons', 'eligibleStudents'));
    }

    public function create(Request $request)
    {
        $students = Student::with(['class', 'violations'])->where('status', 'aktif')->orderBy('name')->get();
        $selectedStudentId = $request->input('student_id');
        $selectedStudent = $selectedStudentId ? Student::with(['class', 'violations.violationType'])->find($selectedStudentId) : null;

        // Generate Roman Month
        $romanMonths = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        $currentMonth = $romanMonths[Carbon::now()->month] ?? 'XI';
        $currentYear = Carbon::now()->year;
        $countThisYear = ParentSummons::whereYear('created_at', $currentYear)->count() + 1;
        $autoLetterNumber = sprintf("%03d/SP-BK/SMK/%s/%d", $countThisYear, $currentMonth, $currentYear);

        return view('summons.create', compact('students', 'selectedStudent', 'autoLetterNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'letter_number' => 'required|string|unique:parent_summons,letter_number',
            'summons_type' => 'required|string',
            'summons_date' => 'required|date',
            'summons_time' => 'required',
            'meeting_place' => 'required|string',
            'reason' => 'required|string',
        ]);

        $student = Student::findOrFail($request->student_id);

        $summons = ParentSummons::create([
            'student_id' => $student->id,
            'letter_number' => $request->letter_number,
            'summons_type' => $request->summons_type,
            'summons_date' => $request->summons_date,
            'summons_time' => $request->summons_time,
            'meeting_place' => $request->meeting_place,
            'reason' => $request->reason,
            'total_points_snapshot' => $student->net_points,
            'status' => 'diterbitkan',
            'created_by' => Auth::id(),
        ]);

        $waLink = null;
        if ($student->parent_phone) {
            $msg = WhatsAppService::formatSummonsMessage($summons);
            $waLink = WhatsAppService::buildWebLink($student->parent_phone, $msg);
        }

        return redirect()->route('summons.show', $summons->id)
            ->with('success', 'Surat Panggilan Orang Tua berhasil diterbitkan.')
            ->with('wa_link', $waLink);
    }

    public function show($id)
    {
        $summons = ParentSummons::with(['student.class', 'creator'])->findOrFail($id);
        $student = $summons->student;

        $waLink = null;
        if ($student->parent_phone) {
            $msg = WhatsAppService::formatSummonsMessage($summons);
            $waLink = WhatsAppService::buildWebLink($student->parent_phone, $msg);
        }

        return view('summons.show', compact('summons', 'waLink'));
    }

    public function print($id)
    {
        $summons = ParentSummons::with(['student.class', 'student.violations.violationType'])->findOrFail($id);
        $school = SchoolSetting::getSettings();

        return view('summons.print', compact('summons', 'school'));
    }

    public function updateResult(Request $request, $id)
    {
        $summons = ParentSummons::findOrFail($id);

        $request->validate([
            'status' => 'required|in:diterbitkan,hadir,dijadwalkan_ulang,tidak_hadir,selesai',
            'meeting_result' => 'nullable|string',
        ]);

        $summons->status = $request->status;
        $summons->meeting_result = $request->meeting_result;
        $summons->save();

        return back()->with('success', 'Status dan hasil pemanggilan berhasil diperbarui.');
    }
}
