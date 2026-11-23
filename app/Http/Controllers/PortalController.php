<?php

namespace App\Http\Controllers;

use App\Models\SchoolSetting;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = null;

        if ($user->isSiswaOrtu()) {
            $student = Student::with(['class.homeroomTeacher', 'violations.violationType.category', 'violations.reporter', 'pointRestorations', 'summons', 'attendanceRecords'])
                ->where('user_id', $user->id)
                ->orWhere('nisn', $user->identifier_number)
                ->first();
        }

        if (!$student) {
            // If logged in as admin/guru/bk browsing portal or no student linked, pick the first student for preview
            $student = Student::with(['class.homeroomTeacher', 'violations.violationType.category', 'violations.reporter', 'pointRestorations', 'summons', 'attendanceRecords'])->first();
        }

        $school = SchoolSetting::getSettings();

        return view('portal.index', compact('student', 'school'));
    }

    public function search(Request $request)
    {
        $school = SchoolSetting::getSettings();
        $student = null;
        $searched = false;

        if ($request->filled('nisn')) {
            $searched = true;
            $student = Student::with(['class.homeroomTeacher', 'violations.violationType.category', 'pointRestorations', 'summons', 'attendanceRecords'])
                ->where('nisn', trim($request->nisn))
                ->first();
        }

        return view('portal.search', compact('school', 'student', 'searched'));
    }
}
