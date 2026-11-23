@extends('layouts.app')

@section('title', 'Input Rekap Presensi Bulanan')
@section('page_title', 'Input Rekapitulasi Presensi Murid Bulanan')

@section('content')
<div class="py-6 space-y-6" x-data="{
    effectiveDays: {{ $effectiveDays ?? 22 }},
    students: {
        @foreach($students as $student)
            @php
                $rec = $existingRecords->get($student->id);
                $sakit = $rec ? (int)$rec->sakit : 0;
                $izin = $rec ? (int)$rec->izin : 0;
                $alpa = $rec ? (int)$rec->alpa : 0;
            @endphp
            '{{ $student->id }}': {
                sakit: {{ $sakit }},
                izin: {{ $izin }},
                alpa: {{ $alpa }}
            },
        @endforeach
    },
    getHadir(id) {
        const s = parseInt(this.students[id]?.sakit || 0);
        const i = parseInt(this.students[id]?.izin || 0);
        const a = parseInt(this.students[id]?.alpa || 0);
        const eff = parseInt(this.effectiveDays || 0);
        return Math.max(0, eff - (s + i + a));
    },
    getPct(id) {
        const eff = parseInt(this.effectiveDays || 0);
        if (eff <= 0) return 100;
        const hadir = this.getHadir(id);
        return Math.round((hadir / eff) * 100);
    },
    resetAllToZero() {
        for (let id in this.students) {
            this.students[id].sakit = 0;
            this.students[id].izin = 0;
            this.students[id].alpa = 0;
        }
    }
}">

    <!-- Filter Bagian Atas: Pilihan Kelas, Bulan, Tahun, dan Hari Efektif -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('attendance.input') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
            
            <!-- Pilih Kelas -->
            <div class="sm:col-span-4">
                <label for="class_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Pilih Kelas / Rombel <span class="text-rose-500">*</span>
                </label>
                <select name="class_id" id="class_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $selectedClass && $selectedClass->id == $c->id ? 'selected' : '' }}>
                            {{ $c->name }} ({{ $c->major ?: $c->level }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Pilih Bulan -->
            <div class="sm:col-span-3">
                <label for="month" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Pilih Bulan <span class="text-rose-500">*</span>
                </label>
                <select name="month" id="month" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Pilih Tahun -->
            <div class="sm:col-span-2">
                <label for="year" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Tahun
                </label>
                <select name="year" id="year" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <!-- Tombol Tampilkan Form -->
            <div class="sm:col-span-3">
                <button type="submit" class="w-full py-2.5 px-4 bg-navy-900 hover:bg-slate-800 text-white font-bold text-sm rounded-xl transition-colors flex items-center justify-center gap-2 shadow-xs">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    <span>Tampilkan Siswa</span>
                </button>
            </div>
        </form>
    </div>

    @if($selectedClass)
    <!-- Form Input Rekapitulasi Presensi Bulanan -->
    <form action="{{ route('attendance.save') }}" method="POST">
        @csrf
        <input type="hidden" name="class_id" value="{{ $selectedClass->id }}">
        <input type="hidden" name="month" value="{{ $month }}">
        <input type="hidden" name="year" value="{{ $year }}">

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            
            <!-- Table Header Toolbar -->
            <div class="p-5 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/70">
                <div>
                    <h3 class="text-base font-extrabold text-slate-800 flex items-center gap-2">
                        <span>Input Rekap Bulanan: Kelas {{ $selectedClass->name }}</span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-semibold">{{ $students->count() }} Siswa</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Bulan: <strong class="text-slate-700">{{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}</strong> &bull; 
                        Wali Kelas: <span class="font-medium text-slate-700">{{ $selectedClass->homeroomTeacher?->name ?? 'Belum ditentukan' }}</span>
                    </p>
                </div>

                <!-- Input Hari Efektif Belajar & Reset Action -->
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-200 shadow-2xs">
                        <label for="effective_days" class="text-xs font-bold text-slate-600 whitespace-nowrap">Hari Efektif:</label>
                        <input type="number" name="effective_days" id="effective_days" min="1" max="31" 
                               x-model.number="effectiveDays"
                               required
                               class="w-16 px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-center text-slate-800 focus:ring-1 focus:ring-emerald-500">
                        <span class="text-xs text-slate-400">Hari</span>
                    </div>

                    <button type="button" @click="resetAllToZero()"
                            class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors flex items-center gap-1.5">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 text-slate-500"></i>
                        <span>Setel 0 (Hadir Penuh)</span>
                    </button>
                </div>
            </div>

            <!-- Info Guide -->
            <div class="px-5 py-3 bg-emerald-50/50 border-b border-emerald-100/60 text-xs text-emerald-800 flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                <span>
                    Cukup isikan jumlah hari <strong>Sakit (S)</strong>, <strong>Izin (I)</strong>, dan <strong>Alpa (A)</strong>. Jumlah <strong>Hadir</strong> dan <strong>% Kehadiran</strong> akan otomatis terhitung.
                </span>
            </div>

            <!-- Student List Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-100/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4 min-w-[200px]">Nama Siswa & NISN</th>
                            <th class="py-3 px-4 text-center w-16">L/P</th>
                            <th class="py-3 px-3 text-center w-28 text-blue-700">Sakit (S)</th>
                            <th class="py-3 px-3 text-center w-28 text-purple-700">Izin (I)</th>
                            <th class="py-3 px-3 text-center w-28 text-rose-700">Alpa (A)</th>
                            <th class="py-3 px-3 text-center w-24 text-emerald-700">Hadir</th>
                            <th class="py-3 px-3 text-center w-24">% Hadir</th>
                            <th class="py-3 px-4 min-w-[180px]">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($students as $idx => $student)
                            @php
                                $rec = $existingRecords->get($student->id);
                                $currNotes = $rec ? $rec->notes : '';
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <!-- No -->
                                <td class="py-3.5 px-4 text-center font-semibold text-slate-400 text-xs">
                                    {{ $idx + 1 }}
                                </td>

                                <!-- Nama & NISN -->
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-800 text-xs sm:text-sm">{{ $student->name }}</div>
                                    <div class="text-[11px] text-slate-400">NISN: {{ $student->nisn }} &bull; NIS: {{ $student->nis ?: '-' }}</div>
                                </td>

                                <!-- Gender -->
                                <td class="py-3.5 px-4 text-center">
                                    <span class="text-xs font-bold px-2 py-0.5 rounded {{ $student->gender == 'L' ? 'bg-sky-50 text-sky-800' : 'bg-pink-50 text-pink-800' }}">
                                        {{ $student->gender }}
                                    </span>
                                </td>

                                <!-- Sakit -->
                                <td class="py-3 px-3 text-center">
                                    <div class="relative">
                                        <input type="number" 
                                               name="recap[{{ $student->id }}][sakit]" 
                                               x-model.number="students['{{ $student->id }}'].sakit"
                                               min="0" max="31"
                                               class="w-full text-center py-2 px-2 bg-blue-50/40 border border-blue-200 rounded-xl text-xs font-bold text-blue-900 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    </div>
                                </td>

                                <!-- Izin -->
                                <td class="py-3 px-3 text-center">
                                    <div class="relative">
                                        <input type="number" 
                                               name="recap[{{ $student->id }}][izin]" 
                                               x-model.number="students['{{ $student->id }}'].izin"
                                               min="0" max="31"
                                               class="w-full text-center py-2 px-2 bg-purple-50/40 border border-purple-200 rounded-xl text-xs font-bold text-purple-900 focus:bg-white focus:ring-2 focus:ring-purple-500 focus:outline-none">
                                    </div>
                                </td>

                                <!-- Alpa -->
                                <td class="py-3 px-3 text-center">
                                    <div class="relative">
                                        <input type="number" 
                                               name="recap[{{ $student->id }}][alpa]" 
                                               x-model.number="students['{{ $student->id }}'].alpa"
                                               min="0" max="31"
                                               class="w-full text-center py-2 px-2 bg-rose-50/40 border border-rose-200 rounded-xl text-xs font-bold text-rose-900 focus:bg-white focus:ring-2 focus:ring-rose-500 focus:outline-none">
                                    </div>
                                </td>

                                <!-- Hadir (Live Calculation) -->
                                <td class="py-3 px-3 text-center">
                                    <span class="inline-block px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 font-extrabold text-xs"
                                          x-text="getHadir('{{ $student->id }}') + ' Hari'">
                                    </span>
                                </td>

                                <!-- % Kehadiran (Live Calculation) -->
                                <td class="py-3 px-3 text-center">
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-black"
                                          :class="getPct('{{ $student->id }}') >= 85 ? 'bg-emerald-100 text-emerald-800' : (getPct('{{ $student->id }}') >= 75 ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800')"
                                          x-text="getPct('{{ $student->id }}') + '%'">
                                    </span>
                                </td>

                                <!-- Catatan / Keterangan -->
                                <td class="py-3 px-4">
                                    <input type="text" name="recap[{{ $student->id }}][notes]" value="{{ $currNotes }}"
                                           placeholder="Keterangan khusus..."
                                           class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:ring-1 focus:ring-emerald-500 focus:bg-white text-slate-700">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-12 text-center text-slate-400 text-sm">
                                    Belum ada data siswa di kelas ini. Silakan tambahkan siswa di menu Master Data Siswa.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Submit Footer -->
            @if($students->isNotEmpty())
                <div class="p-5 border-t border-slate-200 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-xs text-slate-500">
                        Periksa kembali angka Sakit, Izin, dan Alpa sebelum menyimpan rekapitulasi.
                    </p>
                    <div class="flex items-center gap-2.5">
                        <a href="{{ route('attendance.recap', ['class_id' => $selectedClass->id, 'month' => $month, 'year' => $year]) }}" 
                           class="px-4 py-2.5 border border-slate-200 text-slate-700 hover:bg-slate-100 font-bold text-xs rounded-xl transition-colors">
                            Lihat Rekapitulasi &rarr;
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-emerald-700/30 transition-all flex items-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            <span>Simpan Rekapitulasi Presensi</span>
                        </button>
                    </div>
                </div>
            @endif

        </div>
    </form>
    @endif

</div>
@endsection
