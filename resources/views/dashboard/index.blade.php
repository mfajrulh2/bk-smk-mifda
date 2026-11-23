@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Ringkasan Sekolah')

@section('content')
<div class="py-6 space-y-6">

    <!-- Welcome & Quick Action Bar -->
    <div class="bg-gradient-to-r from-navy-900 via-slate-800 to-emerald-950 rounded-2xl p-6 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-semibold mb-2 border border-emerald-500/30">
                <i data-lucide="school" class="w-3.5 h-3.5"></i>
                <span>{{ $school->school_name }} &bull; TA {{ $school->active_academic_year }} (Semester {{ $school->active_semester }})</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight">Selamat Datang, {{ Auth::user()->name }}!</h1>
            <p class="text-xs sm:text-sm text-slate-300 mt-1">Pantau rekapitulasi kehadiran dan kedisiplinan poin siswa secara real-time.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('attendance.input') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-700/30 transition-all">
                <i data-lucide="user-check" class="w-4 h-4"></i>
                <span>Input Rekap Presensi</span>
            </a>
            <a href="{{ route('violations.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl shadow-lg shadow-amber-500/20 transition-all">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                <span>Catat Pelanggaran</span>
            </a>
        </div>
    </div>

    <!-- Metric Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Siswa -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Siswa Aktif</p>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $totalStudents }}</h3>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                    <i data-lucide="door-open" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span>{{ $totalClasses }} Rombongan Belajar</span>
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i data-lucide="graduation-cap" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Tingkat Kehadiran Hari Ini -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Kehadiran Hari Ini</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ $attendanceRateToday }}%</h3>
                <p class="text-xs text-slate-500 mt-1">
                    <span class="text-emerald-700 font-semibold">{{ $todayHadir }} Hadir</span> &bull; 
                    <span class="text-amber-700 font-semibold">{{ $todayTerlambat }} Telat</span> &bull; 
                    <span class="text-rose-700 font-semibold">{{ $todayAlpa }} Alpa</span>
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i data-lucide="calendar-check-2" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Pelanggaran Bulan Ini -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pelanggaran Bulan Ini</p>
                <h3 class="text-2xl font-black text-amber-600 mt-1">{{ $monthlyViolationsCount }} <span class="text-sm font-semibold text-slate-500">kasus</span></h3>
                <p class="text-xs text-slate-500 mt-1">Akumulasi: <span class="font-bold text-amber-600">+{{ $monthlyPoints }} Poin</span></p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i data-lucide="alert-octagon" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Surat Panggilan Menunggu -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Surat Panggilan Ortu</p>
                <h3 class="text-2xl font-black text-rose-600 mt-1">{{ $pendingSummonsCount }}</h3>
                <p class="text-xs text-slate-500 mt-1">Status: Menunggu kedatangan</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <i data-lucide="mail-warning" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Attendance Trend 7 Days (2 Columns) -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-slate-800 text-base">Tren Presensi 7 Hari Terakhir</h3>
                    <p class="text-xs text-slate-500">Jumlah siswa hadir (termasuk terlambat) vs tidak hadir (S/I/A)</p>
                </div>
                <a href="{{ route('attendance.recap') }}" class="text-xs font-semibold text-emerald-600 hover:underline">
                    Lihat Rekap Lengkap &rarr;
                </a>
            </div>
            <div class="h-64">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        <!-- Category Distribution (1 Column) -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-slate-800 text-base">Kategori Pelanggaran</h3>
                    <p class="text-xs text-slate-500">Distribusi jenis kasus tata tertib</p>
                </div>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Two Column Bottom Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Siswa dengan Poin Tertinggi (Perlu Penanganan Khusus BK) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center font-bold">
                        <i data-lucide="flame" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Siswa Perlu Perhatian Khusus</h3>
                        <p class="text-[11px] text-slate-500">Akumulasi poin pelanggaran aktif tertinggi</p>
                    </div>
                </div>
                <a href="{{ route('summons.create') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 px-2.5 py-1.5 rounded-lg border border-rose-200 transition-colors">
                    + Buat SP Ortu
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($studentsWithPoints as $st)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center">
                                {{ substr($st['name'], 0, 2) }}
                            </div>
                            <div>
                                <a href="{{ route('master.students.show', $st['id']) }}" class="font-bold text-slate-800 text-sm hover:text-emerald-600">
                                    {{ $st['name'] }}
                                </a>
                                <p class="text-xs text-slate-500">Kelas: <span class="font-medium text-slate-700">{{ $st['class'] }}</span> &bull; NISN: {{ $st['nisn'] }}</p>
                                <span class="inline-block mt-1 text-[10px] font-semibold px-2 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200">
                                    {{ $st['current_sanction'] }}
                                </span>
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="inline-block px-3 py-1 bg-rose-100 text-rose-800 font-extrabold text-sm rounded-xl">
                                {{ $st['net_points'] }} Poin
                            </span>
                            @if($st['parent_phone'])
                                <div class="mt-1.5">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $st['parent_phone']) }}" target="_blank"
                                       class="inline-flex items-center gap-1 text-[11px] text-emerald-600 font-bold hover:underline">
                                        <i data-lucide="message-circle" class="w-3 h-3"></i>
                                        <span>Hubungi Ortu</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-xs">
                        Belum ada data siswa yang memiliki poin pelanggaran.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pelanggaran Terbaru Dilaporkan -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center font-bold">
                        <i data-lucide="history" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Catatan Pelanggaran Terbaru</h3>
                        <p class="text-[11px] text-slate-500">Laporan ketertiban terkini dari guru</p>
                    </div>
                </div>
                <a href="{{ route('violations.index') }}" class="text-xs font-semibold text-emerald-600 hover:underline">
                    Semua Laporan &rarr;
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recentViolations as $v)
                    <div class="p-4 hover:bg-slate-50 transition-colors">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-800 text-sm">{{ $v->student?->name }}</span>
                                    <span class="text-xs text-slate-400">({{ $v->student?->class?->name }})</span>
                                </div>
                                <p class="text-xs text-amber-700 font-semibold mt-0.5">
                                    {{ $v->violationType?->name }}
                                </p>
                                <p class="text-[11px] text-slate-500 mt-1">
                                    Dilaporkan oleh: <span class="text-slate-700">{{ $v->reporter?->name ?? 'Staf' }}</span> &bull; 
                                    {{ $v->violation_date->translatedFormat('d M Y') }}
                                </p>
                            </div>

                            <div class="text-right shrink-0">
                                <span class="inline-block px-2.5 py-0.5 bg-amber-100 text-amber-800 font-bold text-xs rounded-lg">
                                    +{{ $v->points }} Poin
                                </span>
                                <div class="mt-2">
                                    <a href="{{ route('violations.show', $v->id) }}" class="text-xs text-emerald-600 font-bold hover:underline">
                                        Detail &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-xs">
                        Belum ada laporan pelanggaran tercatat.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
    // Attendance Trend Chart
    const ctxAttendance = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctxAttendance, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    label: 'Hadir',
                    data: {!! json_encode($chartHadir) !!},
                    backgroundColor: '#10b981',
                    borderRadius: 6,
                },
                {
                    label: 'Tidak Hadir (S/I/A)',
                    data: {!! json_encode($chartAbsen) !!},
                    backgroundColor: '#f43f5e',
                    borderRadius: 6,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, font: { size: 11 } }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });

    // Category Distribution Chart
    const ctxCat = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctxCat, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($catLabels) !!},
            datasets: [{
                data: {!! json_encode($catViolationsCount) !!},
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 10, font: { size: 10 } }
                }
            }
        }
    });
</script>
@endpush
@endsection
