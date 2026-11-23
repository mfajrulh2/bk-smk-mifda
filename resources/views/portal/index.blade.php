@extends('layouts.app')

@section('title', 'Portal Siswa & Orang Tua')
@section('page_title', 'Portal Informasi Siswa & Orang Tua')

@section('content')
<div class="py-6 max-w-4xl mx-auto space-y-6">

    @if(!$student)
        <div class="bg-amber-50 p-6 rounded-2xl border border-amber-200 text-center">
            <i data-lucide="alert-circle" class="w-8 h-8 text-amber-600 mx-auto mb-2"></i>
            <h3 class="font-bold text-amber-900 text-base">Akun Belum Terhubung dengan Data Siswa</h3>
            <p class="text-xs text-amber-800 mt-1">
                Silakan hubungi Administrator TU untuk menautkan akun Anda dengan NISN siswa yang bersangkutan.
            </p>
        </div>
    @else

        <!-- Student Profile Card -->
        <div class="bg-gradient-to-r from-navy-950 via-slate-900 to-navy-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                
                <div class="flex items-center gap-4 sm:gap-5">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center font-black text-2xl text-white shadow-lg shrink-0">
                        {{ substr($student->name, 0, 2) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="text-lg sm:text-2xl font-black tracking-tight">{{ $student->name }}</h2>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                {{ $student->status }}
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-300 mt-1">
                            Kelas: <strong>{{ $student->class?->name }}</strong> &bull; NISN: <strong>{{ $student->nisn }}</strong>
                        </p>
                        <p class="text-xs text-slate-400 mt-1">
                            Wali Kelas: <span class="text-slate-200 font-semibold">{{ $student->class?->homeroomTeacher?->name ?? 'Belum ditentukan' }}</span>
                        </p>
                    </div>
                </div>

                <!-- Net Points Big Badge -->
                <div class="p-4 rounded-2xl bg-white/10 border border-white/20 text-center min-w-32 self-start sm:self-auto backdrop-blur-xs">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-400">Total Poin Aktif</p>
                    <p class="text-3xl font-black {{ $student->net_points >= 30 ? 'text-rose-400' : 'text-white' }} mt-0.5">
                        {{ $student->net_points }}
                    </p>
                    <p class="text-[10px] text-slate-400 mt-1">dari batas 100 poin</p>
                </div>

            </div>
        </div>

        <!-- Disciplinary Status & Attendance Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Disciplinary Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Status Disiplin</span>
                        @php $sanction = $student->current_sanction; @endphp
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold
                            {{ $sanction ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $sanction ? 'Perlu Pembinaan' : 'Disiplin Baik' }}
                        </span>
                    </div>

                    <h4 class="font-extrabold text-slate-800 text-base">
                        {{ $sanction ? $sanction->action_name : 'Tidak Ada Sanksi Aktif' }}
                    </h4>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                        {{ $sanction ? $sanction->description : 'Siswa mematuhi peraturan dan tata tertib sekolah dengan baik. Pertahankan kedisiplinan dan prestasi!' }}
                    </p>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 grid grid-cols-2 gap-3 text-center text-xs">
                    <div class="p-2.5 bg-amber-50 rounded-xl">
                        <p class="text-amber-600 font-semibold text-[11px]">Poin Pelanggaran</p>
                        <p class="font-black text-amber-800 text-base mt-0.5">+{{ $student->total_violation_points }}</p>
                    </div>
                    <div class="p-2.5 bg-teal-50 rounded-xl">
                        <p class="text-teal-600 font-semibold text-[11px]">Pemutihan Poin</p>
                        <p class="font-black text-teal-800 text-base mt-0.5">-{{ $student->total_restored_points }}</p>
                    </div>
                </div>
            </div>

            <!-- Attendance Stats Card -->
            @php $att = $student->attendance_stats; @endphp
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rekapitulasi Kehadiran</span>
                        <span class="px-2.5 py-1 rounded-full text-xs font-extrabold {{ $att['percentage'] >= 85 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ $att['percentage'] }}% Hadir
                        </span>
                    </div>

                    <p class="text-xs text-slate-500">
                        Total pencatatan presensi selama semester berjalan: <strong class="text-slate-800">{{ $att['total'] }} hari</strong>.
                    </p>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 grid grid-cols-5 gap-2 text-center text-xs">
                    <div class="p-2 bg-emerald-50 rounded-xl">
                        <p class="font-black text-emerald-800 text-base">{{ $att['hadir'] }}</p>
                        <p class="text-emerald-700 text-[10px] font-semibold">Hadir</p>
                    </div>
                    <div class="p-2 bg-amber-50 rounded-xl">
                        <p class="font-black text-amber-800 text-base">{{ $att['terlambat'] }}</p>
                        <p class="text-amber-700 text-[10px] font-semibold">Telat</p>
                    </div>
                    <div class="p-2 bg-blue-50 rounded-xl">
                        <p class="font-black text-blue-800 text-base">{{ $att['sakit'] }}</p>
                        <p class="text-blue-700 text-[10px] font-semibold">Sakit</p>
                    </div>
                    <div class="p-2 bg-purple-50 rounded-xl">
                        <p class="font-black text-purple-800 text-base">{{ $att['izin'] }}</p>
                        <p class="text-purple-700 text-[10px] font-semibold">Izin</p>
                    </div>
                    <div class="p-2 bg-rose-50 rounded-xl">
                        <p class="font-black text-rose-800 text-base">{{ $att['alpa'] }}</p>
                        <p class="text-rose-700 text-[10px] font-semibold">Alpa</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Active Parent Summons (If Any) -->
        @if($student->summons->isNotEmpty())
            <div class="bg-rose-50 rounded-2xl p-6 border border-rose-200 shadow-xs space-y-3">
                <div class="flex items-center gap-2">
                    <i data-lucide="mail-warning" class="w-5 h-5 text-rose-600"></i>
                    <h3 class="font-black text-rose-950 text-sm">Surat Panggilan Orang Tua dari Sekolah:</h3>
                </div>

                <div class="space-y-3">
                    @foreach($student->summons as $sm)
                        <div class="p-4 bg-white rounded-xl border border-rose-200/80 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <div>
                                <span class="font-mono text-rose-700 font-bold">{{ $sm->letter_number }}</span>
                                <h4 class="font-extrabold text-slate-800 text-sm mt-0.5">{{ $sm->summons_type }}</h4>
                                <p class="text-slate-600 mt-1">
                                    Jadwal: <strong>{{ $sm->summons_date->translatedFormat('l, d F Y') }}</strong> pukul <strong>{{ substr($sm->summons_time, 0, 5) }} WIB</strong>
                                </p>
                                <p class="text-slate-500">Tempat: {{ $sm->meeting_place }}</p>
                            </div>

                            <a href="{{ route('summons.print', $sm->id) }}" target="_blank"
                               class="px-3.5 py-2 bg-navy-900 text-white font-bold text-xs rounded-xl flex items-center gap-1.5 self-start sm:self-auto">
                                <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                                <span>Lihat Surat Resmi</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Violation Log Section -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
                <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                    <i data-lucide="history" class="w-4 h-4 text-slate-500"></i>
                    <span>Riwayat Catatan Pelanggaran Siswa</span>
                </h3>
                <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-200 text-slate-700 font-bold">
                    {{ $student->violations->count() }} Kasus
                </span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($student->violations->sortByDesc('violation_date') as $v)
                    <div class="p-5 hover:bg-slate-50 transition-colors flex flex-col sm:flex-row sm:items-start justify-between gap-4 text-xs">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800 text-sm">{{ $v->violationType?->name }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                    @if($v->status == 'selesai') bg-emerald-100 text-emerald-800
                                    @elseif($v->status == 'dalam_pembinaan') bg-blue-100 text-blue-800
                                    @else bg-amber-100 text-amber-800
                                    @endif">
                                    {{ str_replace('_', ' ', $v->status) }}
                                </span>
                            </div>
                            <p class="text-slate-500">
                                {{ $v->violation_date->translatedFormat('d F Y') }} &bull; Kategori: {{ $v->violationType?->category?->name }}
                            </p>
                            @if($v->notes)
                                <p class="text-slate-700 bg-slate-50 p-2.5 rounded-lg border border-slate-100 mt-1">
                                    Catatan: {{ $v->notes }}
                                </p>
                            @endif
                        </div>

                        <div class="text-right shrink-0">
                            <span class="px-3 py-1 bg-amber-100 text-amber-800 font-black text-xs rounded-xl">
                                +{{ $v->points }} Poin
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-slate-400 text-xs">
                        Tidak ada riwayat pelanggaran tata tertib yang tercatat.
                    </div>
                @endforelse
            </div>
        </div>

    @endif

</div>
@endsection
