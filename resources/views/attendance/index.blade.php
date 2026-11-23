@extends('layouts.app')

@section('title', 'Rekap Presensi Bulanan Per Kelas')
@section('page_title', 'Status Rekapitulasi Presensi Bulanan')

@section('content')
<div class="py-6 space-y-6">

    <!-- Filter Bar Atas: Bulan & Tahun -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('attendance.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
            <div class="sm:col-span-5">
                <label for="month" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pilih Bulan</label>
                <select name="month" id="month" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="sm:col-span-4">
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
                    <span>Terapkan Filter</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Class Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($classes as $c)
            @php $st = $statsPerClass[$c->id] ?? null; @endphp
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase
                            {{ $st && $st['recorded'] ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                            {{ $st && $st['recorded'] ? 'Sudah Di-input' : 'Belum Ada Rekap' }}
                        </span>
                        <span class="text-xs text-slate-400 font-semibold">{{ $c->students_count }} Siswa</span>
                    </div>

                    <h4 class="font-extrabold text-slate-900 text-lg">{{ $c->name }}</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Wali: {{ $c->homeroomTeacher?->name ?? 'Belum ditentukan' }}</p>

                    @if($st && $st['recorded'])
                        <div class="mt-4 pt-3 border-t border-slate-100 grid grid-cols-4 gap-2 text-center text-xs">
                            <div class="p-2 bg-emerald-50 rounded-xl">
                                <p class="font-bold text-emerald-800">{{ $st['hadir'] }}</p>
                                <p class="text-[10px] text-emerald-600">Hadir</p>
                            </div>
                            <div class="p-2 bg-blue-50 rounded-xl">
                                <p class="font-bold text-blue-800">{{ $st['sakit'] }}</p>
                                <p class="text-[10px] text-blue-600">Sakit</p>
                            </div>
                            <div class="p-2 bg-purple-50 rounded-xl">
                                <p class="font-bold text-purple-800">{{ $st['izin'] }}</p>
                                <p class="text-[10px] text-purple-600">Izin</p>
                            </div>
                            <div class="p-2 bg-rose-50 rounded-xl">
                                <p class="font-bold text-rose-800">{{ $st['alpa'] }}</p>
                                <p class="text-[10px] text-rose-600">Alpa</p>
                            </div>
                        </div>

                        <div class="mt-3 flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-medium">Tingkat Kehadiran:</span>
                            <span class="font-black text-emerald-600">{{ $st['percentage'] }}%</span>
                        </div>
                    @else
                        <p class="text-xs text-slate-400 mt-4 pt-3 border-t border-slate-100 italic">
                            Belum ada rekapitulasi presensi untuk periode {{ \Carbon\Carbon::create(null, $month, 1)->translatedFormat('F') }} {{ $year }}.
                        </p>
                    @endif
                </div>

                <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                    <a href="{{ route('attendance.input', ['class_id' => $c->id, 'month' => $month, 'year' => $year]) }}"
                       class="flex-1 py-2 px-3 text-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center justify-center gap-1.5">
                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                        <span>Input Rekap</span>
                    </a>

                    @if($st && $st['recorded'])
                        <a href="{{ route('attendance.recap', ['class_id' => $c->id, 'month' => $month, 'year' => $year]) }}"
                           class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                            Lihat
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
