@extends('layouts.app')

@section('title', 'Rekapitulasi Kehadiran Bulanan')
@section('page_title', 'Rekapitulasi Presensi Bulanan Siswa')

@section('content')
<div class="py-6 space-y-6">

    <!-- Filter Bar Atas -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('attendance.recap') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
            <div class="sm:col-span-4">
                <label for="class_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kelas</label>
                <select name="class_id" id="class_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $selectedClass && $selectedClass->id == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-3">
                <label for="month" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Bulan</label>
                <select name="month" id="month" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="sm:col-span-2">
                <label for="year" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tahun</label>
                <select name="year" id="year" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="sm:col-span-3">
                <button type="submit" class="w-full py-2.5 px-4 bg-navy-900 hover:bg-slate-800 text-white font-bold text-sm rounded-xl transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    <span>Tampilkan Rekap</span>
                </button>
            </div>
        </form>
    </div>

    @if($selectedClass)
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <!-- Header & Action Buttons -->
        <div class="p-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/70">
            <div>
                <h3 class="text-base font-extrabold text-slate-800">
                    Rekapitulasi Kehadiran Kelas {{ $selectedClass->name }}
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Periode: <strong class="text-slate-700">{{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}</strong> &bull; 
                    Hari Efektif: <strong class="text-slate-700">{{ $effectiveDays }} Hari</strong> &bull; 
                    Wali Kelas: <span class="font-medium text-slate-700">{{ $selectedClass->homeroomTeacher?->name ?? 'Belum ditentukan' }}</span>
                </p>
            </div>

            <div class="flex items-center gap-2">
                <!-- Tombol Edit / Input -->
                <a href="{{ route('attendance.input', ['class_id' => $selectedClass->id, 'month' => $month, 'year' => $year]) }}"
                   class="px-3.5 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                    <span>Input / Edit Rekap</span>
                </a>

                <!-- Export Excel CSV -->
                <a href="{{ route('attendance.export', ['class_id' => $selectedClass->id, 'month' => $month, 'year' => $year]) }}"
                   class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                    <span>Unduh Excel</span>
                </a>

                <!-- Print PDF View -->
                <a href="{{ route('attendance.print', ['class_id' => $selectedClass->id, 'month' => $month, 'year' => $year]) }}" target="_blank"
                   class="px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    <span>Cetak Laporan</span>
                </a>
            </div>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4 text-center">NISN</th>
                        <th class="py-3 px-4 text-center">L/P</th>
                        <th class="py-3 px-3 text-center text-slate-700">Efektif</th>
                        <th class="py-3 px-3 text-center text-emerald-700">Hadir</th>
                        <th class="py-3 px-3 text-center text-blue-700">Sakit (S)</th>
                        <th class="py-3 px-3 text-center text-purple-700">Izin (I)</th>
                        <th class="py-3 px-3 text-center text-rose-700">Alpa (A)</th>
                        <th class="py-3 px-4 text-center">% Kehadiran</th>
                        <th class="py-3 px-4">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recapData as $idx => $row)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 text-center font-semibold text-slate-400 text-xs">
                                {{ $idx + 1 }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-800">
                                <a href="{{ route('master.students.show', $row['student']->id) }}" class="hover:text-emerald-600">
                                    {{ $row['student']->name }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4 text-center text-xs text-slate-500 font-mono">
                                {{ $row['student']->nisn }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="text-xs font-bold px-2 py-0.5 rounded {{ $row['student']->gender == 'L' ? 'bg-sky-50 text-sky-800' : 'bg-pink-50 text-pink-800' }}">
                                    {{ $row['student']->gender }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-center font-semibold text-slate-600">
                                {{ $row['effective_days'] }}
                            </td>
                            <td class="py-3.5 px-3 text-center font-bold text-emerald-700 bg-emerald-50/30">
                                {{ $row['hadir'] }}
                            </td>
                            <td class="py-3.5 px-3 text-center font-bold text-blue-700 bg-blue-50/30">
                                {{ $row['sakit'] }}
                            </td>
                            <td class="py-3.5 px-3 text-center font-bold text-purple-700 bg-purple-50/30">
                                {{ $row['izin'] }}
                            </td>
                            <td class="py-3.5 px-3 text-center font-bold text-rose-700 bg-rose-50/30">
                                {{ $row['alpa'] }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-extrabold
                                    {{ $row['percentage'] >= 85 ? 'bg-emerald-100 text-emerald-800' : ($row['percentage'] >= 75 ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                    {{ $row['percentage'] }}%
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-500">
                                {{ $row['notes'] ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="py-12 text-center text-slate-400 text-sm">
                                Belum ada siswa yang terdaftar di kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
    @endif

</div>
@endsection
