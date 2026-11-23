<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\RestorationController;
use App\Http\Controllers\SummonsController;
use App\Http\Controllers\ViolationController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Public Portal Cek Siswa (Orang tua bisa cek via NISN)
Route::get('/cek-siswa', [PortalController::class, 'search'])->name('portal.search');

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Portal Siswa & Orang Tua
    Route::get('/portal', [PortalController::class, 'index'])->name('portal.index');

    // Routes for Staff (Admin, Guru, Guru BK)
    Route::middleware('role:admin,guru,bk')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Presensi / Kehadiran
        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('/', [AttendanceController::class, 'index'])->name('index');
            Route::get('/input', [AttendanceController::class, 'input'])->name('input');
            Route::post('/save', [AttendanceController::class, 'save'])->name('save');
            Route::get('/recap', [AttendanceController::class, 'recap'])->name('recap');
            Route::get('/export-excel', [AttendanceController::class, 'exportExcel'])->name('export');
            Route::get('/print-recap', [AttendanceController::class, 'printRecap'])->name('print');
        });

        // Pelanggaran Siswa
        Route::prefix('violations')->name('violations.')->group(function () {
            Route::get('/', [ViolationController::class, 'index'])->name('index');
            Route::get('/create', [ViolationController::class, 'create'])->name('create');
            Route::post('/store', [ViolationController::class, 'store'])->name('store');
            Route::get('/{id}', [ViolationController::class, 'show'])->name('show');
            Route::put('/{id}/status', [ViolationController::class, 'updateStatus'])->name('update_status');
            Route::delete('/{id}', [ViolationController::class, 'destroy'])->name('destroy');
            Route::get('/export/excel', [ViolationController::class, 'exportExcel'])->name('export');
        });

        // Surat Panggilan Orang Tua
        Route::prefix('summons')->name('summons.')->group(function () {
            Route::get('/', [SummonsController::class, 'index'])->name('index');
            Route::get('/create', [SummonsController::class, 'create'])->name('create');
            Route::post('/store', [SummonsController::class, 'store'])->name('store');
            Route::get('/{id}', [SummonsController::class, 'show'])->name('show');
            Route::get('/{id}/print', [SummonsController::class, 'print'])->name('print');
            Route::put('/{id}/result', [SummonsController::class, 'updateResult'])->name('update_result');
        });

        // Pemutihan / Pengurangan Poin
        Route::prefix('restorations')->name('restorations.')->group(function () {
            Route::get('/', [RestorationController::class, 'index'])->name('index');
            Route::get('/create', [RestorationController::class, 'create'])->name('create');
            Route::post('/store', [RestorationController::class, 'store'])->name('store');
            Route::delete('/{id}', [RestorationController::class, 'destroy'])->name('destroy');
        });
    });

    // Master Data Routes (Admin Only)
    Route::middleware('role:admin')->prefix('master')->name('master.')->group(function () {
        // Siswa
        Route::get('/students', [MasterDataController::class, 'students'])->name('students');
        Route::get('/students/create', [MasterDataController::class, 'studentCreate'])->name('students.create');
        Route::post('/students/store', [MasterDataController::class, 'studentStore'])->name('students.store');
        Route::get('/students/{id}', [MasterDataController::class, 'studentShow'])->name('students.show');
        Route::get('/students/{id}/edit', [MasterDataController::class, 'studentEdit'])->name('students.edit');
        Route::put('/students/{id}', [MasterDataController::class, 'studentUpdate'])->name('students.update');
        Route::delete('/students/{id}', [MasterDataController::class, 'studentDestroy'])->name('students.destroy');
        Route::get('/students-import/template', [MasterDataController::class, 'studentImportTemplate'])->name('students.template');
        Route::post('/students-import/process', [MasterDataController::class, 'studentImportProcess'])->name('students.import');

        // Kelas
        Route::get('/classes', [MasterDataController::class, 'classes'])->name('classes');
        Route::post('/classes/store', [MasterDataController::class, 'classStore'])->name('classes.store');
        Route::put('/classes/{id}', [MasterDataController::class, 'classUpdate'])->name('classes.update');
        Route::delete('/classes/{id}', [MasterDataController::class, 'classDestroy'])->name('classes.destroy');

        // Jenis Pelanggaran & Poin
        Route::get('/violation-types', [MasterDataController::class, 'violationTypes'])->name('violation_types');
        Route::post('/violation-types/store', [MasterDataController::class, 'violationTypeStore'])->name('violation_types.store');
        Route::put('/violation-types/{id}', [MasterDataController::class, 'violationTypeUpdate'])->name('violation_types.update');
        Route::delete('/violation-types/{id}', [MasterDataController::class, 'violationTypeDestroy'])->name('violation_types.destroy');

        // Aturan Sanksi & Thresholds
        Route::get('/thresholds', [MasterDataController::class, 'sanctionThresholds'])->name('thresholds');
        Route::put('/thresholds/{id}', [MasterDataController::class, 'sanctionThresholdUpdate'])->name('thresholds.update');

        // Pengguna / Guru
        Route::get('/users', [MasterDataController::class, 'users'])->name('users');
        Route::post('/users/store', [MasterDataController::class, 'userStore'])->name('users.store');
        Route::put('/users/{id}', [MasterDataController::class, 'userUpdate'])->name('users.update');

        // Pengaturan Sekolah
        Route::get('/settings', [MasterDataController::class, 'settings'])->name('settings');
        Route::put('/settings', [MasterDataController::class, 'settingsUpdate'])->name('settings.update');
    });
});
