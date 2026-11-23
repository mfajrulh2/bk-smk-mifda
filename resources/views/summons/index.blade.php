@extends('layouts.app')

@section('title', 'Surat Panggilan Orang Tua')
@section('page_title', 'Surat Panggilan Orang Tua / Wali Siswa')

@section('content')
<div class="py-6 space-y-6">

    <!-- Top Action & Info Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="font-extrabold text-slate-800 text-base">Manajemen Surat Panggilan Orang Tua (SP 1 / SP 2 / SP 3)</h3>
            <p class="text-xs text-slate-500">Panggilan resmi kedisiplinan dan koordinasi pembinaan siswa dengan orang tua / wali murid</p>
        </div>

        <a href="{{ route('summons.create') }}" 
           class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-rose-600/30 transition-all flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>+ Terbitkan Surat Panggilan Baru</span>
        </a>
    </div>

    <!-- Alert / Siswa Berisiko Tinggi Poin -->
    @if($eligibleStudents->isNotEmpty())
        <div class="bg-amber-50 rounded-2xl p-5 border border-amber-200 shadow-xs">
            <div class="flex items-center gap-2 mb-3">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600"></i>
                <h4 class="font-extrabold text-amber-900 text-sm">Siswa dengan Akumulasi Poin Tinggi (Perlu Tindakan BK):</h4>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($eligibleStudents->take(6) as $st)
                    <div class="bg-white p-3.5 rounded-xl border border-amber-200/80 shadow-2xs flex items-center justify-between">
                        <div>
                            <p class="font-bold text-slate-800 text-xs">{{ $st->name }}</p>
                            <p class="text-[11px] text-slate-500">{{ $st->class?->name }} &bull; Poin: <span class="font-bold text-rose-600">{{ $st->net_points }}</span></p>
                        </div>
                        <a href="{{ route('summons.create', ['student_id' => $st->id]) }}"
                           class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[11px] rounded-lg border border-rose-200 transition-colors">
                            Buat SP &rarr;
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Summons List Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">No. Surat</th>
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">Kelas</th>
                        <th class="py-3 px-4">Jenis Surat</th>
                        <th class="py-3 px-4">Jadwal Kehadiran</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($summons as $idx => $s)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 text-center text-xs font-semibold text-slate-400">
                                {{ $summons->firstItem() + $idx }}
                            </td>
                            <td class="py-3.5 px-4 font-mono text-xs font-bold text-slate-800">
                                {{ $s->letter_number }}
                            </td>
                            <td class="py-3.5 px-4">
                                <a href="{{ route('master.students.show', $s->student_id) }}" class="font-bold text-slate-800 hover:text-emerald-600 text-xs sm:text-sm block">
                                    {{ $s->student?->name }}
                                </a>
                                <span class="text-[11px] text-slate-400">Poin: {{ $s->total_points_snapshot }} Poin</span>
                            </td>
                            <td class="py-3.5 px-4 text-xs font-semibold text-slate-600">
                                {{ $s->student?->class?->name ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-xs font-bold text-rose-700">
                                {{ $s->summons_type }}
                            </td>
                            <td class="py-3.5 px-4 text-xs">
                                <div class="font-bold text-slate-800">{{ $s->summons_date->translatedFormat('d M Y') }}</div>
                                <div class="text-[11px] text-slate-500">{{ substr($s->summons_time, 0, 5) }} WIB di {{ $s->meeting_place }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    @if($s->status == 'selesai') bg-emerald-100 text-emerald-800
                                    @elseif($s->status == 'hadir') bg-blue-100 text-blue-800
                                    @elseif($s->status == 'tidak_hadir') bg-rose-100 text-rose-800
                                    @else bg-amber-100 text-amber-800
                                    @endif">
                                    {{ str_replace('_', ' ', $s->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('summons.show', $s->id) }}"
                                       class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg transition-colors">
                                        Detail
                                    </a>
                                    <a href="{{ route('summons.print', $s->id) }}" target="_blank"
                                       class="px-2.5 py-1 bg-navy-900 hover:bg-slate-800 text-white font-bold text-xs rounded-lg transition-colors flex items-center gap-1">
                                        <i data-lucide="printer" class="w-3 h-3"></i>
                                        <span>Cetak</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400 text-sm">
                                Belum ada surat panggilan orang tua yang diterbitkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($summons->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $summons->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
