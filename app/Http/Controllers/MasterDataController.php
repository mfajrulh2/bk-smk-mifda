<?php

namespace App\Http\Controllers;

use App\Models\ParentSummons;
use App\Models\SanctionThreshold;
use App\Models\SchoolClass;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Models\User;
use App\Models\ViolationCategory;
use App\Models\ViolationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class MasterDataController extends Controller
{
    // ==================== STUDENTS ====================
    public function students(Request $request)
    {
        $query = Student::with(['class', 'violations', 'pointRestorations']);

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('name')->paginate(20)->withQueryString();
        $classes = SchoolClass::all();

        return view('master.students.index', compact('students', 'classes'));
    }

    public function studentCreate()
    {
        $classes = SchoolClass::all();
        return view('master.students.create', compact('classes'));
    }

    public function studentStore(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string|unique:students,nisn|max:20',
            'nis' => 'nullable|string|unique:students,nis|max:20',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'class_id' => 'required|exists:classes,id',
            'phone' => 'nullable|string|max:20',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'create_user_account' => 'nullable|boolean',
        ]);

        $userId = null;
        if ($request->boolean('create_user_account')) {
            $user = User::create([
                'name' => $request->name . ' (Siswa/Ortu)',
                'username' => $request->nisn,
                'email' => $request->nisn . '@siswa.sekolah.sch.id',
                'password' => Hash::make($request->nisn), // default password = NISN
                'role' => 'siswa_ortu',
                'phone' => $request->parent_phone,
                'identifier_number' => $request->nisn,
            ]);
            $userId = $user->id;
        }

        $student = Student::create([
            'nisn' => $request->nisn,
            'nis' => $request->nis,
            'name' => $request->name,
            'gender' => $request->gender,
            'class_id' => $request->class_id,
            'phone' => $request->phone,
            'parent_name' => $request->parent_name,
            'parent_phone' => $request->parent_phone,
            'address' => $request->address,
            'user_id' => $userId,
            'status' => 'aktif',
        ]);

        return redirect()->route('master.students')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function studentShow($id)
    {
        $student = Student::with(['class', 'violations.violationType.category', 'violations.reporter', 'pointRestorations', 'summons', 'attendanceRecords'])
            ->findOrFail($id);

        return view('master.students.show', compact('student'));
    }

    public function studentEdit($id)
    {
        $student = Student::findOrFail($id);
        $classes = SchoolClass::all();
        return view('master.students.edit', compact('student', 'classes'));
    }

    public function studentUpdate(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'nisn' => 'required|string|max:20|unique:students,nisn,' . $student->id,
            'nis' => 'nullable|string|max:20|unique:students,nis,' . $student->id,
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'class_id' => 'required|exists:classes,id',
            'phone' => 'nullable|string|max:20',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:aktif,lulus,mutasi,drop_out',
        ]);

        $student->update($request->all());

        return redirect()->route('master.students.show', $student->id)->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function studentDestroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
        return redirect()->route('master.students')->with('success', 'Data siswa berhasil dihapus.');
    }

    public function studentImportTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"template_import_siswa.csv\"",
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['NISN', 'NIS', 'Nama Siswa', 'Jenis Kelamin (L/P)', 'Nama Kelas', 'Nama Orang Tua', 'No WA Ortu', 'Alamat']);
            fputcsv($handle, ['0081234590', '26050', 'Contoh Siswa Baru', 'L', 'X RPL 1', 'Nama Bapak/Ibu', '081234567890', 'Jl. Merdeka No. 10']);
            fputcsv($handle, ['0081234591', '26051', 'Contoh Siswi Baru', 'P', 'X TKJ 1', 'Nama Bapak/Ibu', '081234567891', 'Jl. Sudirman No. 20']);
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function studentImportProcess(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        // Check BOM
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $header = fgetcsv($handle, 1000, ',');
        $imported = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            if (empty($row[0]) || empty($row[2])) {
                continue;
            }

            $nisn = trim($row[0]);
            $nis = isset($row[1]) ? trim($row[1]) : null;
            $name = trim($row[2]);
            $gender = isset($row[3]) && strtoupper(trim($row[3])) === 'P' ? 'P' : 'L';
            $className = isset($row[4]) ? trim($row[4]) : 'X RPL 1';
            $parentName = isset($row[5]) ? trim($row[5]) : null;
            $parentPhone = isset($row[6]) ? trim($row[6]) : null;
            $address = isset($row[7]) ? trim($row[7]) : null;

            // Find or create class
            $class = SchoolClass::firstOrCreate(
                ['name' => $className],
                ['level' => 'X', 'major' => 'Umum']
            );

            // Check if student exists
            $student = Student::where('nisn', $nisn)->first();
            if ($student) {
                $student->update([
                    'nis' => $nis ?: $student->nis,
                    'name' => $name,
                    'gender' => $gender,
                    'class_id' => $class->id,
                    'parent_name' => $parentName,
                    'parent_phone' => $parentPhone,
                    'address' => $address,
                ]);
                $skipped++;
            } else {
                Student::create([
                    'nisn' => $nisn,
                    'nis' => $nis,
                    'name' => $name,
                    'gender' => $gender,
                    'class_id' => $class->id,
                    'parent_name' => $parentName,
                    'parent_phone' => $parentPhone,
                    'address' => $address,
                    'status' => 'aktif',
                ]);
                $imported++;
            }
        }

        fclose($handle);

        return back()->with('success', "Proses impor selesai! {$imported} siswa baru ditambahkan, {$skipped} siswa diperbarui.");
    }

    // ==================== CLASSES ====================
    public function classes()
    {
        $classes = SchoolClass::with('homeroomTeacher')->withCount('students')->get();
        $teachers = User::whereIn('role', ['guru', 'admin'])->get();
        return view('master.classes.index', compact('classes', 'teachers'));
    }

    public function classStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:classes,name|max:50',
            'level' => 'required|string|max:10',
            'major' => 'nullable|string|max:100',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
            'academic_year' => 'nullable|string|max:20',
        ]);

        SchoolClass::create($request->all());

        return back()->with('success', 'Kelas baru berhasil ditambahkan.');
    }

    public function classUpdate(Request $request, $id)
    {
        $class = SchoolClass::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:50|unique:classes,name,' . $class->id,
            'level' => 'required|string|max:10',
            'major' => 'nullable|string|max:100',
            'homeroom_teacher_id' => 'nullable|exists:users,id',
        ]);

        $class->update($request->all());

        return back()->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function classDestroy($id)
    {
        $class = SchoolClass::findOrFail($id);
        $class->delete();
        return back()->with('success', 'Kelas berhasil dihapus.');
    }

    // ==================== VIOLATION TYPES ====================
    public function violationTypes()
    {
        $categories = ViolationCategory::with('violationTypes')->get();
        return view('master.violations.index', compact('categories'));
    }

    public function violationTypeStore(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:violation_categories,id',
            'name' => 'required|string|max:255',
            'points' => 'required|integer|min:1',
            'default_sanction' => 'nullable|string|max:255',
        ]);

        ViolationType::create($request->all());

        return back()->with('success', 'Jenis pelanggaran baru berhasil ditambahkan.');
    }

    public function violationTypeUpdate(Request $request, $id)
    {
        $type = ViolationType::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'points' => 'required|integer|min:1',
            'default_sanction' => 'nullable|string|max:255',
        ]);

        $type->update($request->all());

        return back()->with('success', 'Jenis pelanggaran berhasil diperbarui.');
    }

    public function violationTypeDestroy($id)
    {
        $type = ViolationType::findOrFail($id);
        $type->delete();
        return back()->with('success', 'Jenis pelanggaran berhasil dihapus.');
    }

    // ==================== SANCTION THRESHOLDS ====================
    public function sanctionThresholds()
    {
        $thresholds = SanctionThreshold::orderBy('min_points')->get();
        return view('master.thresholds.index', compact('thresholds'));
    }

    public function sanctionThresholdUpdate(Request $request, $id)
    {
        $threshold = SanctionThreshold::findOrFail($id);
        $threshold->update([
            'min_points' => $request->min_points,
            'action_name' => $request->action_name,
            'description' => $request->description,
            'requires_summons' => $request->boolean('requires_summons'),
        ]);

        return back()->with('success', 'Aturan sanksi berhasil diperbarui.');
    }

    // ==================== USERS ====================
    public function users()
    {
        $users = User::orderBy('name')->paginate(15);
        return view('master.users.index', compact('users'));
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:50',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,guru,bk,siswa_ortu',
            'phone' => 'nullable|string|max:20',
            'identifier_number' => 'nullable|string|max:50',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'identifier_number' => $request->identifier_number,
        ]);

        return back()->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,guru,bk,siswa_ortu',
            'phone' => 'nullable|string|max:20',
            'identifier_number' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->phone = $request->phone;
        $user->identifier_number = $request->identifier_number;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Data pengguna berhasil diperbarui.');
    }

    // ==================== SETTINGS ====================
    public function settings()
    {
        $settings = SchoolSetting::getSettings();
        return view('master.settings.index', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        $settings = SchoolSetting::getSettings();

        $request->validate([
            'school_name' => 'required|string|max:255',
            'school_address' => 'nullable|string|max:255',
            'school_phone' => 'nullable|string|max:50',
            'school_email' => 'nullable|email|max:255',
            'principal_name' => 'nullable|string|max:255',
            'principal_nip' => 'nullable|string|max:50',
            'counseling_head_name' => 'nullable|string|max:255',
            'counseling_head_nip' => 'nullable|string|max:50',
            'active_academic_year' => 'required|string|max:20',
            'active_semester' => 'required|in:Ganjil,Genap',
            'wa_gateway_token' => 'nullable|string|max:255',
            'wa_gateway_endpoint' => 'nullable|string|max:255',
        ]);

        $settings->update($request->all());

        return back()->with('success', 'Pengaturan sekolah dan WhatsApp gateway berhasil diperbarui.');
    }
}
