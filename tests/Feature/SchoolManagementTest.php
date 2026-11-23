<?php

namespace Tests\Feature;

use App\Models\AttendanceRecord;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentViolation;
use App\Models\User;
use App\Models\ViolationType;
use Tests\TestCase;

class SchoolManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->seed();
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('E-DISIPLIN SEKOLAH');
    }

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->post('/login', [
            'login' => $admin->username,
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($admin);

        $dashResponse = $this->actingAs($admin)->get('/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Dashboard Ringkasan Sekolah');
    }

    public function test_attendance_can_be_saved(): void
    {
        $guru = User::where('role', 'guru')->first();
        $class = SchoolClass::first();
        $student = Student::where('class_id', $class->id)->first();

        $response = $this->actingAs($guru)->post(route('attendance.save'), [
            'class_id' => $class->id,
            'month' => 11,
            'year' => 2026,
            'effective_days' => 22,
            'recap' => [
                $student->id => [
                    'sakit' => 2,
                    'izin' => 1,
                    'alpa' => 0,
                    'notes' => 'Sakit demam',
                ]
            ]
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('monthly_attendances', [
            'student_id' => $student->id,
            'class_id' => $class->id,
            'month' => 11,
            'year' => 2026,
            'effective_days' => 22,
            'hadir' => 19, // 22 - (2 + 1 + 0)
            'sakit' => 2,
            'izin' => 1,
            'alpa' => 0,
        ]);
    }

    public function test_violation_create_page_has_class_filter_and_students(): void
    {
        $guru = User::where('role', 'guru')->first();

        $response = $this->actingAs($guru)->get(route('violations.create'));
        $response->assertStatus(200);
        $response->assertSee('Filter Berdasarkan Kelas');
        $response->assertSee('Cari Nama / NISN Murid');
    }

    public function test_violation_can_be_recorded(): void
    {
        $guru = User::where('role', 'guru')->first();
        $student = Student::first();
        $violationType = ViolationType::first();

        $response = $this->actingAs($guru)->post(route('violations.store'), [
            'student_id' => $student->id,
            'violation_type_id' => $violationType->id,
            'violation_date' => date('Y-m-d'),
            'violation_time' => '07:30',
            'notes' => 'Catatan pengujian pelanggaran',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('student_violations', [
            'student_id' => $student->id,
            'violation_type_id' => $violationType->id,
            'notes' => 'Catatan pengujian pelanggaran',
        ]);
    }

    public function test_public_nisn_portal_search(): void
    {
        $student = Student::first();

        $response = $this->get(route('portal.search', ['nisn' => $student->nisn]));
        $response->assertStatus(200);
        $response->assertSee($student->name);
        $response->assertSee($student->nisn);
    }
}
