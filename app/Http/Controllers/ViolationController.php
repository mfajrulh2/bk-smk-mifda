<?php

namespace App\Http\Controllers;

use App\Models\ParentSummons;
use App\Models\SchoolClass;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Models\StudentViolation;
use App\Models\ViolationCategory;
use App\Models\ViolationType;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ViolationController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentViolation::with(['student.class', 'violationType.category', 'reporter', 'handledBy']);

        if ($request->filled('class_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->filled('category_id')) {
            $query->whereHas('violationType', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

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

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('violation_date', [$request->start_date, $request->end_date]);
        }

        $violations = $query->latest('violation_date')->latest('id')->paginate(15)->withQueryString();
        $classes = SchoolClass::all();
        $categories = ViolationCategory::all();

        return view('violations.index', compact('violations', 'classes', 'categories'));
    }

    public function create(Request $request)
    {
        $classes = SchoolClass::all();
        $categories = ViolationCategory::with('violationTypes')->get();
        $students = Student::with('class')->where('status', 'aktif')->orderBy('name')->get();

        $selectedStudentId = $request->input('student_id');

        return view('violations.create', compact('classes', 'categories', 'students', 'selectedStudentId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'violation_type_id' => 'required|exists:violation_types,id',
            'violation_date' => 'required|date',
            'violation_time' => 'nullable|string',
            'notes' => 'nullable|string',
            'proof_image' => 'nullable|image|max:3072', // max 3MB
        ]);

        $violationType = ViolationType::findOrFail($request->violation_type_id);
        $student = Student::findOrFail($request->student_id);

        $proofPath = null;
        if ($request->hasFile('proof_image')) {
            $proofPath = $request->file('proof_image')->store('violations', 'public');
        }

        $violation = StudentViolation::create([
            'student_id' => $student->id,
            'violation_type_id' => $violationType->id,
            'violation_date' => $request->violation_date,
            'violation_time' => $request->violation_time ?: Carbon::now()->format('H:i:s'),
            'points' => $violationType->points,
            'reporter_id' => Auth::id(),
            'notes' => $request->notes,
            'proof_image' => $proofPath,
            'status' => 'menunggu_tindakan',
        ]);

        // Check if student now crosses a sanction threshold requiring summons
        $netPoints = $student->net_points;
        $sanction = $student->current_sanction;

        $waLink = null;
        if ($student->parent_phone) {
            $msg = WhatsAppService::formatViolationMessage($violation);
            $waLink = WhatsAppService::buildWebLink($student->parent_phone, $msg);
        }

        $message = "Pelanggaran berhasil dicatat (+{$violationType->points} poin). Total poin siswa saat ini: {$netPoints} poin.";

        if ($sanction && $sanction->requires_summons) {
            $message .= " ⚠️ PERINGATAN: Siswa telah mencapai sanksi '{$sanction->action_name}' dan disarankan menerbitkan Surat Panggilan Orang Tua.";
        }

        return redirect()->route('violations.show', $violation->id)
            ->with('success', $message)
            ->with('wa_link', $waLink);
    }

    public function show($id)
    {
        $violation = StudentViolation::with(['student.class', 'violationType.category', 'reporter', 'handledBy'])->findOrFail($id);
        $student = $violation->student;

        $waLink = null;
        if ($student->parent_phone) {
            $msg = WhatsAppService::formatViolationMessage($violation);
            $waLink = WhatsAppService::buildWebLink($student->parent_phone, $msg);
        }

        return view('violations.show', compact('violation', 'waLink'));
    }

    public function updateStatus(Request $request, $id)
    {
        $violation = StudentViolation::findOrFail($id);

        $request->validate([
            'status' => 'required|in:menunggu_tindakan,dalam_pembinaan,selesai',
            'follow_up_action' => 'required|string|max:1000',
        ]);

        $violation->status = $request->status;
        $violation->follow_up_action = $request->follow_up_action;
        $violation->handled_by = Auth::id();
        $violation->handled_at = Carbon::now();
        $violation->save();

        return back()->with('success', 'Status tindak lanjut pembinaan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $violation = StudentViolation::findOrFail($id);

        if ($violation->proof_image && Storage::disk('public')->exists($violation->proof_image)) {
            Storage::disk('public')->delete($violation->proof_image);
        }

        $violation->delete();

        return redirect()->route('violations.index')->with('success', 'Data pelanggaran berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        $query = StudentViolation::with(['student.class', 'violationType.category', 'reporter']);

        if ($request->filled('class_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        $violations = $query->latest('violation_date')->get();
        $school = SchoolSetting::getSettings();
        $filename = "Laporan_Pelanggaran_Siswa_" . date('Y-m-d') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($violations, $school) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [$school->school_name]);
            fputcsv($handle, ["REKAP DATA PELANGGARAN KEDISIPLINAN SISWA"]);
            fputcsv($handle, ["Tanggal Cetak: " . Carbon::now()->translatedFormat('d F Y H:i')]);
            fputcsv($handle, []);

            fputcsv($handle, ['No', 'Tanggal', 'Waktu', 'NISN', 'Nama Siswa', 'Kelas', 'Kategori', 'Jenis Pelanggaran', 'Poin', 'Guru Pelapor', 'Status Pembinaan', 'Tindak Lanjut']);

            $no = 1;
            foreach ($violations as $v) {
                fputcsv($handle, [
                    $no++,
                    $v->violation_date->format('Y-m-d'),
                    $v->violation_time ?? '-',
                    $v->student?->nisn ?? '-',
                    $v->student?->name ?? '-',
                    $v->student?->class?->name ?? '-',
                    $v->violationType?->category?->name ?? '-',
                    $v->violationType?->name ?? '-',
                    $v->points,
                    $v->reporter?->name ?? '-',
                    strtoupper(str_replace('_', ' ', $v->status)),
                    $v->follow_up_action ?? '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
