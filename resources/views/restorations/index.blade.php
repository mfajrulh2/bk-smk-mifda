@extends('layouts.app')

@section('title', 'Pemutihan / Pengurangan Poin')
@section('page_title', 'Pemutihan & Pengurangan Poin Siswa')

@section('content')
<div class="py-6 space-y-6">

    <!-- Top Action Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="font-extrabold text-slate-800 text-base">Riwayat Pemutihan Poin Kedisiplinan</h3>
            <p class="text-xs text-slate-500">Pencatatan reward perbaikan sikap, konseling rutin, tugas sosial, atau prestasi siswa</p>
        </div>

        <a href="{{ route('restorations.create') }}" 
           class="px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-teal-600/30 transition-all flex items-center gap-2">
            <i data-lucide="sparkles" class="w-4 h-4"></i>
            <span>+ Catat Pemutihan Poin</span>
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">Kelas</th>
                        <th class="py-3 px-4 text-center">Poin Dipulihkan</th>
                        <th class="py-3 px-4">Alasan Pemutihan</th>
                        <th class="py-3 px-4">Petugas BK / Guru</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($restorations as $idx => $r)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 text-center text-xs font-semibold text-slate-400">
                                {{ $restorations->firstItem() + $idx }}
                            </td>
                            <td class="py-3.5 px-4 text-xs font-bold text-slate-800">
                                {{ $r->restoration_date->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <a href="{{ route('master.students.show', $r->student_id) }}" class="font-bold text-slate-800 hover:text-emerald-600 text-xs sm:text-sm block">
                                    {{ $r->student?->name }}
                                </a>
                                <span class="text-[11px] text-slate-400">NISN: {{ $r->student?->nisn }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-xs font-semibold text-slate-600">
                                {{ $r->student?->class?->name ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block px-3 py-1 rounded-xl bg-teal-100 text-teal-800 font-black text-xs">
                                    -{{ $r->restored_points }} Poin
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-700">
                                <div class="font-semibold">{{ $r->reason }}</div>
                                @if($r->notes)
                                    <div class="text-[11px] text-slate-400 mt-0.5">{{ $r->notes }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-600">
                                {{ $r->officer?->name ?? 'Petugas BK' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <form action="{{ route('restorations.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Hapus catatan pemutihan poin ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400 text-sm">
                                Belum ada riwayat pemutihan poin yang dicatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($restorations->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $restorations->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
