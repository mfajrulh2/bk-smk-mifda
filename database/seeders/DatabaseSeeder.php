<?php

namespace Database\Seeders;

use App\Models\AttendanceRecord;
use App\Models\MonthlyAttendance;
use App\Models\ParentSummons;
use App\Models\PointRestoration;
use App\Models\SanctionThreshold;
use App\Models\SchoolClass;
use App\Models\SchoolSetting;
use App\Models\Student;
use App\Models\StudentViolation;
use App\Models\User;
use App\Models\ViolationCategory;
use App\Models\ViolationType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Bersihkan data dinamis sebelum seeder jika dijalankan ulang
        MonthlyAttendance::query()->delete();
        AttendanceRecord::query()->delete();
        PointRestoration::query()->delete();
        ParentSummons::query()->delete();
        StudentViolation::query()->delete();

        // 1. School Settings
        SchoolSetting::updateOrCreate(['id' => 1], [
            'school_name' => 'SMK / SMA NEGERI 1 TELADAN BANGSA',
            'school_code' => 'NPSN-20261101',
            'school_address' => 'Jl. Pendidikan Karakter No. 45, Kompleks Pendidikan, Jakarta',
            'school_phone' => '(021) 7788-9900',
            'school_email' => 'admin@smkn1teladan.sch.id',
            'principal_name' => 'Drs. H. Bambang Sudirman, M.M.',
            'principal_nip' => '19680315 199403 1 004',
            'counseling_head_name' => 'Hj. Ratna Indrayani, S.Pd., M.Psi.',
            'counseling_head_nip' => '19800720 200604 2 011',
            'active_academic_year' => '2026/2027',
            'active_semester' => 'Ganjil',
        ]);

        // 2. Default Users
        $admin = User::updateOrCreate(['username' => 'admin'], [
            'name' => 'Administrator TU',
            'email' => 'admin@sekolah.sch.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'identifier_number' => 'ADM-001',
        ]);

        $guruBK = User::updateOrCreate(['username' => 'guru_bk'], [
            'name' => 'Hj. Ratna Indrayani, S.Pd., M.Psi.',
            'email' => 'bk@sekolah.sch.id',
            'password' => Hash::make('bk123'),
            'role' => 'bk',
            'phone' => '081234567891',
            'identifier_number' => '19800720 200604 2 011',
        ]);

        $wali1 = User::updateOrCreate(['username' => 'wali_kelas'], [
            'name' => 'Ahmad Syukron, S.Kom.',
            'email' => 'syukron@sekolah.sch.id',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
            'phone' => '081234567892',
            'identifier_number' => '19850912 201001 1 014',
        ]);

        $guruPiket = User::updateOrCreate(['username' => 'guru_piket'], [
            'name' => 'Dewi Lestari, S.Pd.',
            'email' => 'dewi@sekolah.sch.id',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
            'phone' => '081234567893',
            'identifier_number' => '19900214 201502 2 008',
        ]);

        // 3. Sanction Thresholds
        $thresholds = [
            [
                'min_points' => 15,
                'action_name' => 'Peringatan Lisan & Pembinaan Wali Kelas',
                'description' => 'Siswa dipanggil oleh Wali Kelas untuk diberi teguran lisan dan pencatatan komitmen disiplin.',
                'requires_summons' => false,
            ],
            [
                'min_points' => 30,
                'action_name' => 'Surat Peringatan I (SP 1) & Bimbingan BK',
                'description' => 'Diterbitkan Surat Peringatan I, siswa wajib mengikuti sesi bimbingan konseling di ruang BK.',
                'requires_summons' => false,
            ],
            [
                'min_points' => 50,
                'action_name' => 'Surat Peringatan II (SP 2) & Pemanggilan Orang Tua I',
                'description' => 'Diterbitkan SP II dan Surat Panggilan Orang Tua tahap 1 untuk klarifikasi dan penandatanganan surat perjanjian.',
                'requires_summons' => true,
            ],
            [
                'min_points' => 75,
                'action_name' => 'Surat Peringatan III (SP 3) & Pemanggilan Orang Tua Terakhir',
                'description' => 'Diterbitkan SP III, orang tua diundang menghadap Kepala Sekolah dan Tim Ketertiban Sekolah.',
                'requires_summons' => true,
            ],
            [
                'min_points' => 100,
                'action_name' => 'Skorsing / Sidang Dewan Guru / Dikembalikan ke Orang Tua',
                'description' => 'Tindakan tegas skorsing sementara atau dikembalikan kepada orang tua melalui sidang dewan guru.',
                'requires_summons' => true,
            ],
        ];

        foreach ($thresholds as $th) {
            SanctionThreshold::updateOrCreate(['min_points' => $th['min_points']], $th);
        }

        // 4. Violation Categories & Types
        $categoriesData = [
            'Kedisiplinan & Waktu' => [
                'desc' => 'Pelanggaran terkait ketepatan waktu dan aturan kehadiran harian.',
                'types' => [
                    ['name' => 'Terlambat hadir ke sekolah (10 - 30 menit)', 'points' => 5, 'sanction' => 'Teguran lisan & piket kebersihan'],
                    ['name' => 'Terlambat lebih dari 30 menit tanpa keterangan sah', 'points' => 10, 'sanction' => 'Surat teguran & pembinaan wali kelas'],
                    ['name' => 'Keluar gerbang sekolah saat jam pelajaran tanpa izin', 'points' => 20, 'sanction' => 'Tugas mandiri di perpustakaan & SP 1'],
                    ['name' => 'Membolos jam pelajaran tertentu (Cabut)', 'points' => 15, 'sanction' => 'Pembinaan guru mata pelajaran & wali kelas'],
                    ['name' => 'Tidak masuk sekolah tanpa kabar/keterangan (Alpa)', 'points' => 10, 'sanction' => 'Konfirmasi orang tua via telepon'],
                ]
            ],
            'Kerapian & Atribut' => [
                'desc' => 'Pelanggaran pakaian seragam, atribut kelengkapan, dan penampilan fisik.',
                'types' => [
                    ['name' => 'Pakaian seragam tidak dimasukkan / tidak sesuai hari', 'points' => 5, 'sanction' => 'Teguran & perapian seragam langsung'],
                    ['name' => 'Tidak memakai dasi, sabuk, atau topi saat upacara', 'points' => 5, 'sanction' => 'Barisan khusus upacara'],
                    ['name' => 'Memakai sepatu dan kaos kaki tidak sesuai ketentuan', 'points' => 5, 'sanction' => 'Teguran & penggantian kaos kaki'],
                    ['name' => 'Rambut gondrong/diwarnai bagi siswa putra', 'points' => 10, 'sanction' => 'Diberi batas waktu 2 hari untuk potong rapi'],
                    ['name' => 'Menggunakan perhiasan/make-up berlebihan bagi siswi', 'points' => 5, 'sanction' => 'Penghapusan make-up di tempat'],
                    ['name' => 'Memakai tato temporer/permanen atau tindik bagi putra', 'points' => 25, 'sanction' => 'Pembinaan khusus BK & panggilan ortu'],
                ]
            ],
            'Etika & Sopan Santun' => [
                'desc' => 'Tingkah laku terhadap guru, teman, staf, dan lingkungan sekolah.',
                'types' => [
                    ['name' => 'Makan/minum di dalam kelas saat KBM berlangsung tanpa izin', 'points' => 5, 'sanction' => 'Teguran guru pengajar'],
                    ['name' => 'Bermain HP/Game saat jam pelajaran berlangsung', 'points' => 10, 'sanction' => 'HP disita dan diambil saat pulang sekolah'],
                    ['name' => 'Berbicara kasar/tidak sopan kepada guru atau tenaga kependidikan', 'points' => 30, 'sanction' => 'Permintaan maaf tertulis & pembinaan BK'],
                    ['name' => 'Melakukan perundungan/bullying secara verbal terhadap teman', 'points' => 35, 'sanction' => 'Mediasi BK & surat pernyataan bermaterai'],
                    ['name' => 'Membuang sampah sembarangan / merusak tanaman sekolah', 'points' => 10, 'sanction' => 'Operasi semut membersihkan taman sekolah'],
                ]
            ],
            'Pelanggaran Berat & Norma Hukum' => [
                'desc' => 'Tindakan yang melanggar hukum, ketertiban umum, dan norma kesusilaan.',
                'types' => [
                    ['name' => 'Membawa/merokok atau vape di lingkungan sekolah & radius 100m', 'points' => 40, 'sanction' => 'Sita barang bukti & Surat Panggilan Orang Tua'],
                    ['name' => 'Terlibat perkelahian/tawuran antar pelajar', 'points' => 75, 'sanction' => 'Skorsing 1 minggu & SP 3'],
                    ['name' => 'Membawa senjata tajam atau benda berbahaya ke sekolah', 'points' => 80, 'sanction' => 'Penyitaan, pemanggilan ortu, koordinasi aparat'],
                    ['name' => 'Mencuri barang milik sekolah atau teman', 'points' => 60, 'sanction' => 'Ganti rugi barang & pembinaan ketat BK'],
                    ['name' => 'Membawa/mengonsumsi miras atau narkoba', 'points' => 100, 'sanction' => 'Dikembalikan ke orang tua / proses hukum'],
                    ['name' => 'Melakukan tindakan asusila / menyebarkan konten pornografi', 'points' => 85, 'sanction' => 'Sidang dewan kehormatan & SP 3'],
                ]
            ]
        ];

        foreach ($categoriesData as $catName => $catDetails) {
            $cat = ViolationCategory::updateOrCreate(['name' => $catName], [
                'description' => $catDetails['desc']
            ]);

            foreach ($catDetails['types'] as $type) {
                ViolationType::updateOrCreate([
                    'category_id' => $cat->id,
                    'name' => $type['name']
                ], [
                    'points' => $type['points'],
                    'default_sanction' => $type['sanction'],
                ]);
            }
        }

        // 5. Classes
        $classes = [
            ['name' => 'X RPL 1', 'level' => 'X', 'major' => 'Rekayasa Perangkat Lunak', 'homeroom_teacher_id' => $wali1->id],
            ['name' => 'X TKJ 1', 'level' => 'X', 'major' => 'Teknik Komputer Jaringan', 'homeroom_teacher_id' => $guruPiket->id],
            ['name' => 'XI RPL 1', 'level' => 'XI', 'major' => 'Rekayasa Perangkat Lunak', 'homeroom_teacher_id' => null],
            ['name' => 'XI TKJ 1', 'level' => 'XI', 'major' => 'Teknik Komputer Jaringan', 'homeroom_teacher_id' => null],
            ['name' => 'XII RPL 1', 'level' => 'XII', 'major' => 'Rekayasa Perangkat Lunak', 'homeroom_teacher_id' => null],
        ];

        $classModels = [];
        foreach ($classes as $c) {
            $classModels[$c['name']] = SchoolClass::updateOrCreate(['name' => $c['name']], [
                'level' => $c['level'],
                'major' => $c['major'],
                'homeroom_teacher_id' => $c['homeroom_teacher_id'],
                'academic_year' => '2026/2027',
            ]);
        }

        // 6. Students
        $studentsData = [
            // X RPL 1
            ['nisn' => '0081234561', 'nis' => '26001', 'name' => 'Aditya Pratama Putra', 'gender' => 'L', 'class' => 'X RPL 1', 'parent_name' => 'Bambang Supriyanto', 'parent_phone' => '081234567001', 'address' => 'Jl. Mawar No. 12, Jakarta'],
            ['nisn' => '0081234562', 'nis' => '26002', 'name' => 'Alya Rahmadhani', 'gender' => 'P', 'class' => 'X RPL 1', 'parent_name' => 'H. Gunawan Santoso', 'parent_phone' => '081234567002', 'address' => 'Jl. Melati Blok C No. 5, Jakarta'],
            ['nisn' => '0081234563', 'nis' => '26003', 'name' => 'Bintang Ramadhan', 'gender' => 'L', 'class' => 'X RPL 1', 'parent_name' => 'Rahmat Hidayat', 'parent_phone' => '081234567003', 'address' => 'Jl. Kenanga No. 88, Jakarta'],
            ['nisn' => '0081234564', 'nis' => '26004', 'name' => 'Chelsea Olivia Kusuma', 'gender' => 'P', 'class' => 'X RPL 1', 'parent_name' => 'Kusuma Wardhana', 'parent_phone' => '081234567004', 'address' => 'Jl. Cempaka No. 14, Jakarta'],
            ['nisn' => '0081234565', 'nis' => '26005', 'name' => 'Dwi Cahyo Nugroho', 'gender' => 'L', 'class' => 'X RPL 1', 'parent_name' => 'Suryanto', 'parent_phone' => '081234567005', 'address' => 'Jl. Anggrek No. 20, Jakarta'],
            ['nisn' => '0081234566', 'nis' => '26006', 'name' => 'Fajar Maulana Malik', 'gender' => 'L', 'class' => 'X RPL 1', 'parent_name' => 'Malik Ibrahim', 'parent_phone' => '081234567006', 'address' => 'Jl. Kemuning No. 3, Jakarta'],
            ['nisn' => '0081234567', 'nis' => '26007', 'name' => 'Gilang Arya Dewantara', 'gender' => 'L', 'class' => 'X RPL 1', 'parent_name' => 'Agus Dewantara', 'parent_phone' => '081234567007', 'address' => 'Jl. Dahlia No. 45, Jakarta'],
            ['nisn' => '0081234568', 'nis' => '26008', 'name' => 'Hana Fitria Azzahra', 'gender' => 'P', 'class' => 'X RPL 1', 'parent_name' => 'M. Mansyur', 'parent_phone' => '081234567008', 'address' => 'Jl. Flamboyan No. 19, Jakarta'],
            ['nisn' => '0081234569', 'nis' => '26009', 'name' => 'Ilham Wahyudi Saputra', 'gender' => 'L', 'class' => 'X RPL 1', 'parent_name' => 'Wahyudi', 'parent_phone' => '081234567009', 'address' => 'Jl. Teratai No. 60, Jakarta'],
            ['nisn' => '0081234570', 'nis' => '26010', 'name' => 'Jessica Anindya Putri', 'gender' => 'P', 'class' => 'X RPL 1', 'parent_name' => 'Eko Prasetyo', 'parent_phone' => '081234567010', 'address' => 'Jl. Bougenville No. 22, Jakarta'],

            // X TKJ 1
            ['nisn' => '0081234571', 'nis' => '26011', 'name' => 'Kevin Rizky Ananda', 'gender' => 'L', 'class' => 'X TKJ 1', 'parent_name' => 'Ananda Ruslan', 'parent_phone' => '081234567011', 'address' => 'Jl. Garuda No. 11, Jakarta'],
            ['nisn' => '0081234572', 'nis' => '26012', 'name' => 'Lestari Wulandari', 'gender' => 'P', 'class' => 'X TKJ 1', 'parent_name' => 'Joko Widodo', 'parent_phone' => '081234567012', 'address' => 'Jl. Rajawali No. 7, Jakarta'],
            ['nisn' => '0081234573', 'nis' => '26013', 'name' => 'Muhammad Farhan Kamil', 'gender' => 'L', 'class' => 'X TKJ 1', 'parent_name' => 'Kamil Siddiq', 'parent_phone' => '081234567013', 'address' => 'Jl. Merpati No. 33, Jakarta'],
            ['nisn' => '0081234574', 'nis' => '26014', 'name' => 'Nabila Syakieb', 'gender' => 'P', 'class' => 'X TKJ 1', 'parent_name' => 'Syakieb Ali', 'parent_phone' => '081234567014', 'address' => 'Jl. Cendrawasih No. 15, Jakarta'],
            ['nisn' => '0081234575', 'nis' => '26015', 'name' => 'Oki Setiawan', 'gender' => 'L', 'class' => 'X TKJ 1', 'parent_name' => 'Budi Setiawan', 'parent_phone' => '081234567015', 'address' => 'Jl. Kasuari No. 9, Jakarta'],

            // XI RPL 1
            ['nisn' => '0071234581', 'nis' => '25001', 'name' => 'Panji Gumilang Pangestu', 'gender' => 'L', 'class' => 'XI RPL 1', 'parent_name' => 'Pangestu Tri', 'parent_phone' => '081234567016', 'address' => 'Jl. Diponegoro No. 101, Jakarta'],
            ['nisn' => '0071234582', 'nis' => '25002', 'name' => 'Qonita Salma', 'gender' => 'P', 'class' => 'XI RPL 1', 'parent_name' => 'Salma Harun', 'parent_phone' => '081234567017', 'address' => 'Jl. Sudirman No. 55, Jakarta'],
            ['nisn' => '0071234583', 'nis' => '25003', 'name' => 'Rendi Septian', 'gender' => 'L', 'class' => 'XI RPL 1', 'parent_name' => 'Septian Nur', 'parent_phone' => '081234567018', 'address' => 'Jl. Thamrin No. 80, Jakarta'],
            ['nisn' => '0071234584', 'nis' => '25004', 'name' => 'Siti Aisyah Humaira', 'gender' => 'P', 'class' => 'XI RPL 1', 'parent_name' => 'Lukman Hakim', 'parent_phone' => '081234567019', 'address' => 'Jl. Gatot Subroto No. 4, Jakarta'],
            ['nisn' => '0071234585', 'nis' => '25005', 'name' => 'Teguh Prakoso', 'gender' => 'L', 'class' => 'XI RPL 1', 'parent_name' => 'Prakoso Hadi', 'parent_phone' => '081234567020', 'address' => 'Jl. Rasuna Said No. 12, Jakarta'],
        ];

        $studentModels = [];
        foreach ($studentsData as $idx => $s) {
            $class = $classModels[$s['class']] ?? null;
            if (!$class) continue;

            // Buat akun portal ortu/siswa untuk siswa ke-7 (Gilang) sebagai contoh
            $userId = null;
            if ($s['nisn'] === '0081234567') {
                $userSiswa = User::updateOrCreate(['username' => 'siswa1'], [
                    'name' => 'Gilang Arya Dewantara (Siswa/Ortu)',
                    'email' => 'gilang.siswa@sekolah.sch.id',
                    'password' => Hash::make('siswa123'),
                    'role' => 'siswa_ortu',
                    'phone' => $s['parent_phone'],
                    'identifier_number' => $s['nisn'],
                ]);
                $userId = $userSiswa->id;
            }

            $studentModel = Student::updateOrCreate(['nisn' => $s['nisn']], [
                'nis' => $s['nis'],
                'name' => $s['name'],
                'gender' => $s['gender'],
                'class_id' => $class->id,
                'phone' => '0813998877' . str_pad($idx, 2, '0', STR_PAD_LEFT),
                'parent_name' => $s['parent_name'],
                'parent_phone' => $s['parent_phone'],
                'address' => $s['address'],
                'user_id' => $userId,
                'status' => 'aktif',
            ]);

            $studentModels[] = $studentModel;
        }

        // 7. Monthly Attendance Records
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        foreach ($studentModels as $st) {
            $sakit = ($st->nisn === '0081234567') ? 2 : (rand(0, 10) > 8 ? 1 : 0);
            $izin = ($st->nisn === '0081234567') ? 1 : (rand(0, 10) > 8 ? 1 : 0);
            $alpa = ($st->nisn === '0081234567') ? 2 : (rand(0, 10) > 9 ? 1 : 0);
            $effectiveDays = 22;
            $hadir = max(0, $effectiveDays - ($sakit + $izin + $alpa));

            MonthlyAttendance::updateOrCreate([
                'student_id' => $st->id,
                'month' => $currentMonth,
                'year' => $currentYear,
            ], [
                'class_id' => $st->class_id,
                'academic_year' => '2026/2027',
                'effective_days' => $effectiveDays,
                'hadir' => $hadir,
                'sakit' => $sakit,
                'izin' => $izin,
                'alpa' => $alpa,
                'notes' => $alpa > 0 ? 'Perlu konfirmasi orang tua' : null,
                'recorded_by' => $wali1->id,
            ]);
        }

        // 8. Sample Violations
        // Ambil beberapa violation types
        $vTerlambat = ViolationType::where('name', 'like', '%Terlambat hadir%')->first();
        $vMerokok = ViolationType::where('name', 'like', '%merokok atau vape%')->first();
        $vMembolos = ViolationType::where('name', 'like', '%Membolos%')->first();
        $vHP = ViolationType::where('name', 'like', '%Bermain HP%')->first();
        $vBullying = ViolationType::where('name', 'like', '%bullying%')->first();

        // Kasus 1: Gilang Arya Dewantara (Mencapai akumulasi poin 55 -> perlu SP 2 & Surat Panggilan Ortu)
        $gilang = Student::where('nisn', '0081234567')->first();
        if ($gilang && $vMerokok && $vTerlambat && $vHP) {
            StudentViolation::create([
                'student_id' => $gilang->id,
                'violation_type_id' => $vTerlambat->id,
                'violation_date' => Carbon::now()->subDays(12)->format('Y-m-d'),
                'violation_time' => '07:25:00',
                'points' => $vTerlambat->points,
                'reporter_id' => $guruPiket->id,
                'notes' => 'Terlambat masuk sekolah saat pintu gerbang sudah ditutup.',
                'status' => 'selesai',
                'follow_up_action' => 'Diberi pembinaan lisan oleh guru piket dan membersihkan koridor.',
                'handled_by' => $guruPiket->id,
                'handled_at' => Carbon::now()->subDays(12),
            ]);

            StudentViolation::create([
                'student_id' => $gilang->id,
                'violation_type_id' => $vHP->id,
                'violation_date' => Carbon::now()->subDays(8)->format('Y-m-d'),
                'violation_time' => '10:15:00',
                'points' => $vHP->points,
                'reporter_id' => $wali1->id,
                'notes' => 'Asyik main game Mobile Legends saat pelajaran matematika berlangsung.',
                'status' => 'selesai',
                'follow_up_action' => 'HP diamankan selama KBM dan diambil setelah jam pulang.',
                'handled_by' => $wali1->id,
                'handled_at' => Carbon::now()->subDays(8),
            ]);

            StudentViolation::create([
                'student_id' => $gilang->id,
                'violation_type_id' => $vMerokok->id,
                'violation_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'violation_time' => '12:45:00',
                'points' => $vMerokok->points,
                'reporter_id' => $wali1->id,
                'notes' => 'Kedapatan merokok di area belakang kantin sekolah bersama teman.',
                'status' => 'dalam_pembinaan',
                'follow_up_action' => 'Diterbitkan Surat Panggilan Orang Tua untuk pembinaan lanjutan di BK.',
                'handled_by' => $guruBK->id,
                'handled_at' => Carbon::now()->subDays(1),
            ]);

            // Terbitkan Surat Panggilan Orang Tua untuk Gilang
            ParentSummons::updateOrCreate(['letter_number' => '014/SP-BK/SMK/XI/2026'], [
                'student_id' => $gilang->id,
                'summons_type' => 'Surat Panggilan Orang Tua I (SP 2)',
                'summons_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
                'summons_time' => '09:00',
                'meeting_place' => 'Ruang Bimbingan & Konseling (BK)',
                'reason' => 'Akumulasi poin pelanggaran mencapai 55 poin (termasuk pelanggaran membawa dan merokok di lingkungan sekolah). Diperlukan klarifikasi dan penandatanganan surat komitmen bersama.',
                'total_points_snapshot' => 55,
                'status' => 'diterbitkan',
                'created_by' => $guruBK->id,
            ]);
        }

        // Kasus 2: Dwi Cahyo Nugroho (15 poin -> Peringatan lisan)
        $dwi = Student::where('nisn', '0081234565')->first();
        if ($dwi && $vMembolos) {
            StudentViolation::create([
                'student_id' => $dwi->id,
                'violation_type_id' => $vMembolos->id,
                'violation_date' => Carbon::now()->subDays(4)->format('Y-m-d'),
                'violation_time' => '11:00:00',
                'points' => $vMembolos->points,
                'reporter_id' => $wali1->id,
                'notes' => 'Tidak masuk ke kelas pada jam ke-5 dan berada di warung luar pagar.',
                'status' => 'dalam_pembinaan',
                'follow_up_action' => 'Peringatan lisan oleh Wali Kelas.',
                'handled_by' => $wali1->id,
                'handled_at' => Carbon::now()->subDays(3),
            ]);
        }

        // Kasus 3: Kevin Rizky (Ada pemutihan poin karena membantu kegiatan sekolah)
        $kevin = Student::where('nisn', '0081234571')->first();
        if ($kevin && $vTerlambat) {
            StudentViolation::create([
                'student_id' => $kevin->id,
                'violation_type_id' => $vTerlambat->id,
                'violation_date' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'violation_time' => '07:20:00',
                'points' => 5,
                'reporter_id' => $guruPiket->id,
                'notes' => 'Terlambat 10 menit.',
                'status' => 'selesai',
                'handled_by' => $guruPiket->id,
            ]);

            PointRestoration::create([
                'student_id' => $kevin->id,
                'restored_points' => 5,
                'restoration_date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'reason' => 'Konseling dan partisipasi aktif sebagai petugas paduan suara upacara bendera.',
                'notes' => 'Siswa menunjukkan perubahan sikap positif dan disiplin.',
                'officer_id' => $guruBK->id,
            ]);
        }
    }
}
