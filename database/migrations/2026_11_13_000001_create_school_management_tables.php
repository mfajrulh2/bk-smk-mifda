<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
            $table->string('role')->default('guru')->after('password'); // admin, guru, bk, siswa_ortu
            $table->string('phone')->nullable()->after('role');
            $table->string('identifier_number')->nullable()->after('phone'); // NIP / NISN
            $table->string('avatar')->nullable()->after('identifier_number');
        });

        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();
            $table->string('school_name')->default('SMA / SMK NEGERI 1 CONTOH');
            $table->string('school_code')->nullable();
            $table->string('school_address')->nullable()->default('Jl. Pendidikan No. 123');
            $table->string('school_phone')->nullable()->default('021-1234567');
            $table->string('school_email')->nullable()->default('sekolah@example.sch.id');
            $table->string('principal_name')->nullable()->default('Drs. H. Ahmad Fauzi, M.Pd.');
            $table->string('principal_nip')->nullable()->default('19700101 199512 1 001');
            $table->string('counseling_head_name')->nullable()->default('Siti Nurhaliza, S.Pd., Kons.');
            $table->string('counseling_head_nip')->nullable()->default('19820510 200801 2 005');
            $table->string('active_academic_year')->default('2026/2027');
            $table->enum('active_semester', ['Ganjil', 'Genap'])->default('Ganjil');
            $table->string('wa_gateway_token')->nullable();
            $table->string('wa_gateway_endpoint')->nullable();
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });

        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "X TKJ 1", "XI MIPA 2"
            $table->string('level'); // X, XI, XII / 7, 8, 9
            $table->string('major')->nullable(); // IPA, IPS, RPL, TKJ, dll.
            $table->foreignId('homeroom_teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('academic_year')->default('2026/2027');
            $table->timestamps();
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 20)->unique();
            $table->string('nis', 20)->nullable()->unique();
            $table->string('name');
            $table->enum('gender', ['L', 'P'])->default('L');
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->string('phone')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('parent_phone')->nullable(); // No WhatsApp Ortu
            $table->text('address')->nullable();
            $table->string('photo')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['aktif', 'lulus', 'mutasi', 'drop_out'])->default('aktif');
            $table->timestamps();
        });

        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->date('date');
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpa', 'terlambat'])->default('hadir');
            $table->string('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['student_id', 'date']);
            $table->index(['class_id', 'date']);
        });

        Schema::create('violation_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Kedisiplinan, Kerapian, Kerajinan, Etika/Moral, Berat/Kriminal
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('violation_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('violation_categories')->cascadeOnDelete();
            $table->string('name'); // e.g. "Terlambat masuk sekolah", "Merokok di lingkungan sekolah"
            $table->integer('points')->default(5);
            $table->string('default_sanction')->nullable(); // e.g. "Teguran lisan & pembinaan"
            $table->timestamps();
        });

        Schema::create('student_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('violation_type_id')->constrained('violation_types')->cascadeOnDelete();
            $table->date('violation_date');
            $table->time('violation_time')->nullable();
            $table->integer('points');
            $table->foreignId('reporter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->string('proof_image')->nullable();
            $table->enum('status', ['menunggu_tindakan', 'dalam_pembinaan', 'selesai'])->default('menunggu_tindakan');
            $table->text('follow_up_action')->nullable();
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('handled_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'violation_date']);
        });

        Schema::create('point_restorations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->integer('restored_points')->default(5);
            $table->date('restoration_date');
            $table->string('reason'); // "Konseling rutin & perbaikan sikap", "Prestasi akademik", dll.
            $table->text('notes')->nullable();
            $table->foreignId('officer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('sanction_thresholds', function (Blueprint $table) {
            $table->id();
            $table->integer('min_points'); // 15, 30, 50, 75, 100
            $table->string('action_name'); // Peringatan Lisan, SP 1, SP 2, SP 3 (Panggilan Ortu), Skorsing
            $table->text('description')->nullable();
            $table->boolean('requires_summons')->default(false); // butuh surat panggilan ortu
            $table->timestamps();
        });

        Schema::create('parent_summons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('letter_number')->unique(); // e.g. "042/SP-BK/SMK/XI/2026"
            $table->string('summons_type')->default('Surat Panggilan Orang Tua I'); // SP 1, SP 2, SP 3
            $table->date('summons_date'); // Tanggal orang tua harus datang
            $table->time('summons_time')->default('08:30');
            $table->string('meeting_place')->default('Ruang Bimbingan Konseling (BK)');
            $table->text('reason'); // Rincian pelanggaran / alasan pemanggilan
            $table->integer('total_points_snapshot');
            $table->enum('status', ['diterbitkan', 'hadir', 'dijadwalkan_ulang', 'tidak_hadir', 'selesai'])->default('diterbitkan');
            $table->text('meeting_result')->nullable(); // Catatan hasil pertemuan ortu
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_summons');
        Schema::dropIfExists('sanction_thresholds');
        Schema::dropIfExists('point_restorations');
        Schema::dropIfExists('student_violations');
        Schema::dropIfExists('violation_types');
        Schema::dropIfExists('violation_categories');
        Schema::dropIfExists('attendance_records');
        Schema::dropIfExists('students');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('school_settings');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'role', 'phone', 'identifier_number', 'avatar']);
        });
    }
};
