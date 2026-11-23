<?php

namespace App\Http\Controllers;

use App\Models\PointRestoration;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestorationController extends Controller
{
    public function index(Request $request)
    {
        $query = PointRestoration::with(['student.class', 'officer']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $restorations = $query->latest('restoration_date')->latest('id')->paginate(15)->withQueryString();

        return view('restorations.index', compact('restorations'));
    }

    public function create(Request $request)
    {
        $students = Student::with(['class', 'violations', 'pointRestorations'])
            ->where('status', 'aktif')
            ->get()
            ->filter(function ($s) {
                return $s->net_points > 0;
            })
            ->sortBy('name');

        $selectedStudentId = $request->input('student_id');

        return view('restorations.create', compact('students', 'selectedStudentId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'restored_points' => 'required|integer|min:1',
            'restoration_date' => 'required|date',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $student = Student::findOrFail($request->student_id);

        if ($request->restored_points > $student->net_points) {
            return back()->withErrors([
                'restored_points' => "Poin yang dipulihkan ({$request->restored_points}) tidak boleh melebihi sisa poin aktif siswa ({$student->net_points} poin)."
            ])->withInput();
        }

        PointRestoration::create([
            'student_id' => $student->id,
            'restored_points' => $request->restored_points,
            'restoration_date' => $request->restoration_date,
            'reason' => $request->reason,
            'notes' => $request->notes,
            'officer_id' => Auth::id(),
        ]);

        return redirect()->route('restorations.index')
            ->with('success', "Pemutihan poin sebanyak {$request->restored_points} poin untuk siswa {$student->name} berhasil dicatat.");
    }

    public function destroy($id)
    {
        $restoration = PointRestoration::findOrFail($id);
        $restoration->delete();

        return back()->with('success', 'Data pemutihan poin berhasil dihapus.');
    }
}
