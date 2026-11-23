@extends('layouts.app')

@section('title', 'Profil & Catatan Siswa: ' . $student->name)
@section('page_title', 'Dossier Kedisiplinan & Presensi Siswa')

@section('content')
<div class="py-6 space-y-6">

    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('master.students') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Database Siswa</span>
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('violations.create', ['student_id' => $student->id]) }}" 
               class="px-3.5 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                <span>+ Catat Pelanggaran</span>
            </a>

            @if($student->net_points > 0)
                <a href="{{ route('restorations.create', ['student_id' => $student->id]) }}" 
                   class="px-3.5 py-2 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    <span>+ Pemutihan Poin</span>
                </a>
            @endif

            <a href="{{ route('master.students.edit', $student->id) }}"
               class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors flex items-center gap-1.5">
                <i data-lucide="edit-2" class="w-4 h-4"></i>
                <span>Edit Biodata</span>
            </a>
        </div>
    </div>

    <!-- Student Header Dossier -->
    <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-navy-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            
            <div class="flex items-start sm:items-center gap-5">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center font-black text-2xl text-white shadow-lg shrink-0">
                    {{ substr($student->name, 0, 2) }}
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-xl sm:text-2xl font-black tracking-tight">{{ $student->name }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase
                            {{ $student->gender == 'L' ? 'bg-sky-500/20 text-sky-300 border border-sky-500/30' : 'bg-pink-500/20 text-pink-300 border border-pink-500/30' }}">
                            {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            {{ $student->status }}
                        </span>
                    </div>

                    <p class="text-xs sm:text-sm text-slate-300 mt-1 flex flex-wrap items-center gap-3">
                        <span>Kelas: <strong>{{ $student->class?->name }}</strong></span>
                        <span>&bull;</span>
                        <span>NISN: <strong>{{ $student->nisn }}</strong></span>
                        <span>&bull;</span>
                        <span>NIS: <strong>{{ $student->nis ?: '-' }}</strong></span>
                    </p>

                    <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-slate-300">
                        <span>Orang Tua: <strong>{{ $student->parent_name ?: '-' }}</strong></span>
                        @if($student->parent_phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->parent_phone) }}" target="_blank"
                               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-600/30 text-emerald-300 hover:bg-emerald-600/50 border border-emerald-500/30 transition-colors">
                                <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                                <span>{{ $student->parent_phone }}</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Score Badge Cards -->
            <div class="flex items-center gap-3 self-start md:self-auto">
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 text-center min-w-28 backdrop-blur-xs">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Poin Pelanggaran</p>
                    <p class="text-2xl font-black text-amber-400 mt-0.5">+{{ $student->total_violation_points }}</p>
                </div>

                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 text-center min-w-28 backdrop-blur-xs">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pemutihan Poin</p>
                    <p class="text-2xl font-black text-teal-400 mt-0.5">-{{ $student->total_restored_points }}</p>
                </div>

                <div class="p-4 rounded-2xl bg-white/10 border border-white/20 text-center min-w-28 backdrop-blur-xs">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-400">Poin Bersih</p>
                    <p class="text-2xl font-black {{ $student->net_points > 30 ? 'text-rose-400' : 'text-white' }} mt-0.5">
                        {{ $student->net_points }}
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- Attendance Stats & Current Sanction Status -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Current Sanction Box -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Status Disiplin Siswa</p>
                @php $sanction = $student->current_sanction; @endphp
                <h4 class="text-base font-extrabold text-slate-800 mt-1">
                    {{ $sanction ? $sanction->action_name : 'Berkelakuan Baik (Disiplin)' }}
                </h4>
                <p class="text-xs text-slate-600 mt-1">
                    {{ $sanction ? $sanction->description : 'Belum mencapai ambang batas sanksi atau surat peringatan tata tertib sekolah.' }}
                </p>
            </div>

            @if($sanction && $sanction->requires_summons)
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <a href="{{ route('summons.create', ['student_id' => $student->id]) }}" 
                       class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-600 hover:text-rose-700">
                        <i data-lucide="mail-warning" class="w-4 h-4"></i>
                        <span>Terbitkan Surat Panggilan Ortu &rarr;</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- Attendance Stats (2 Columns) -->
        @php $att = $student->attendance_stats; @endphp
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs md:col-span-2">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ringkasan Presensi Siswa</p>
                <span class="text-xs font-extrabold px-2.5 py-0.5 rounded-full {{ $att['percentage'] >= 85 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                    {{ $att['percentage'] }}% Kehadiran
                </span>
            </div>

            <div class="grid grid-cols-5 gap-2 text-center text-xs">
                <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                    <p class="font-extrabold text-emerald-800 text-lg">{{ $att['hadir'] }}</p>
                    <p class="text-emerald-700 text-[10px] font-semibold mt-0.5">Hadir</p>
                </div>
                <div class="p-3 bg-amber-50 rounded-xl border border-amber-100">
                    <p class="font-extrabold text-amber-800 text-lg">{{ $att['terlambat'] }}</p>
                    <p class="text-amber-700 text-[10px] font-semibold mt-0.5">Telat</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-xl border border-blue-100">
                    <p class="font-extrabold text-blue-800 text-lg">{{ $att['sakit'] }}</p>
                    <p class="text-blue-700 text-[10px] font-semibold mt-0.5">Sakit</p>
                </div>
                <div class="p-3 bg-purple-50 rounded-xl border border-purple-100">
                    <p class="font-extrabold text-purple-800 text-lg">{{ $att['izin'] }}</p>
                    <p class="text-purple-700 text-[10px] font-semibold mt-0.5">Izin</p>
                </div>
                <div class="p-3 bg-rose-50 rounded-xl border border-rose-100">
                    <p class="font-extrabold text-rose-800 text-lg">{{ $att['alpa'] }}</p>
                    <p class="text-rose-700 text-[10px] font-semibold mt-0.5">Alpa</p>
                </div>
            </div>
        </div>

    </div>

    <!-- History Tab Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden" x-data="{ tab: 'violations' }">
        
        <!-- Navigation Tab Headers -->
        <div class="border-b border-slate-200 px-6 flex items-center gap-6 text-xs font-bold">
            <button @click="tab = 'violations'" :class="tab === 'violations' ? 'border-emerald-600 text-emerald-600 border-b-2' : 'text-slate-500 hover:text-slate-800'" class="py-4 transition-colors flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                <span>Catatan Pelanggaran ({{ $student->violations->count() }})</span>
            </button>
            <button @click="tab = 'summons'" :class="tab === 'summons' ? 'border-emerald-600 text-emerald-600 border-b-2' : 'text-slate-500 hover:text-slate-800'" class="py-4 transition-colors flex items-center gap-2">
                <i data-lucide="mail-warning" class="w-4 h-4"></i>
                <span>Surat Panggilan Ortu ({{ $student->summons->count() }})</span>
            </button>
            <button @click="tab = 'restorations'" :class="tab === 'restorations' ? 'border-emerald-600 text-emerald-600 border-b-2' : 'text-slate-500 hover:text-slate-800'" class="py-4 transition-colors flex items-center gap-2">
                <i data-lucide="sparkles" class="w-4 h-4"></i>
                <span>Pemutihan Poin ({{ $student->pointRestorations->count() }})</span>
            </button>
        </div>

        <!-- Tab 1: Violations -->
        <div x-show="tab === 'violations'" class="divide-y divide-slate-100">
            @forelse($student->violations->sortByDesc('violation_date') as $v)
                <div class="p-5 hover:bg-slate-50 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-800 text-sm">{{ $v->violationType?->name }}</span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase
                                @if($v->status == 'selesai') bg-emerald-100 text-emerald-800
                                @elseif($v->status == 'dalam_pembinaan') bg-blue-100 text-blue-800
                                @else bg-amber-100 text-amber-800
                                @endif">
                                {{ str_replace('_', ' ', $v->status) }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500">
                            {{ $v->violation_date->translatedFormat('d F Y') }} &bull; Dilaporkan oleh: {{ $v->reporter?->name ?? 'Staf' }}
                        </p>
                        @if($v->notes)
                            <p class="text-xs text-slate-700 italic">"{{ $v->notes }}"</p>
                        @endif
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 bg-amber-100 text-amber-800 font-black text-xs rounded-xl">
                            +{{ $v->points }} Poin
                        </span>
                        <a href="{{ route('violations.show', $v->id) }}" class="p-2 bg-slate-100 hover:bg-emerald-600 hover:text-white rounded-lg transition-colors text-xs font-bold">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-slate-400 text-xs">
                    Siswa ini tidak memiliki catatan pelanggaran. Sangat baik!
                </div>
            @endforelse
        </div>

        <!-- Tab 2: Summons -->
        <div x-show="tab === 'summons'" x-cloak class="divide-y divide-slate-100">
            @forelse($student->summons->sortByDesc('summons_date') as $s)
                <div class="p-5 hover:bg-slate-50 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="font-mono text-xs font-bold text-slate-800">{{ $s->letter_number }}</span>
                        <h4 class="font-bold text-rose-700 text-sm mt-0.5">{{ $s->summons_type }}</h4>
                        <p class="text-xs text-slate-500 mt-1">
                            Jadwal: {{ $s->summons_date->translatedFormat('l, d F Y') }} pukul {{ substr($s->summons_time, 0, 5) }} WIB di {{ $s->meeting_place }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('summons.show', $s->id) }}" class="px-3 py-1.5 bg-slate-100 text-slate-700 font-bold text-xs rounded-lg">
                            Detail
                        </a>
                        <a href="{{ route('summons.print', $s->id) }}" target="_blank" class="px-3 py-1.5 bg-navy-900 text-white font-bold text-xs rounded-lg flex items-center gap-1">
                            <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                            <span>Cetak</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-slate-400 text-xs">
                    Belum pernah diterbitkan surat panggilan untuk siswa ini.
                </div>
            @endforelse
        </div>

        <!-- Tab 3: Restorations -->
        <div x-show="tab === 'restorations'" x-cloak class="divide-y divide-slate-100">
            @forelse($student->pointRestorations->sortByDesc('restoration_date') as $r)
                <div class="p-5 hover:bg-slate-50 transition-colors flex items-center justify-between">
                    <div>
                        <p class="font-bold text-slate-800 text-sm">{{ $r->reason }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Tanggal: {{ $r->restoration_date->translatedFormat('d F Y') }} &bull; Oleh: {{ $r->officer?->name ?? 'Petugas BK' }}
                        </p>
                    </div>

                    <span class="px-3 py-1 bg-teal-100 text-teal-800 font-black text-xs rounded-xl">
                        -{{ $r->restored_points }} Poin
                    </span>
                </div>
            @empty
                <div class="p-12 text-center text-slate-400 text-xs">
                    Belum ada riwayat pemutihan poin untuk siswa ini.
                </div>
            @endforelse
        </div>

    </div>

</div>
@endsection
