@extends('layouts.app')

@section('title', 'Data Pelanggaran Siswa')
@section('page_title', 'Daftar Kasus Pelanggaran Siswa')

@section('content')
<div class="py-6 space-y-6">

    <!-- Top Action & Filter Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-extrabold text-slate-800 text-base">Buku Catatan Kedisiplinan Siswa</h3>
                <p class="text-xs text-slate-500">Seluruh riwayat pelanggaran tata tertib yang dilaporkan oleh dewan guru</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('violations.export', request()->all()) }}"
                   class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                    <span>Export Excel</span>
                </a>

                <a href="{{ route('violations.create') }}" 
                   class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-extrabold rounded-xl shadow-md shadow-amber-500/20 transition-all flex items-center gap-1.5">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    <span>+ Catat Kasus Baru</span>
                </a>
            </div>
        </div>

        <form action="{{ route('violations.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-3 border-t border-slate-100 items-end">
            
            <div class="sm:col-span-4">
                <label for="search" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Cari Siswa</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       placeholder="Nama siswa atau NISN..."
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-1 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="sm:col-span-3">
                <label for="class_id" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Filter Kelas</label>
                <select name="class_id" id="class_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-3">
                <label for="category_id" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Kategori</label>
                <select name="category_id" id="category_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <button type="submit" class="w-full py-2 bg-navy-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition-colors flex items-center justify-center gap-1.5">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Terapkan</span>
                </button>
            </div>
        </form>

    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Tanggal Kejadian</th>
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">Kelas</th>
                        <th class="py-3 px-4">Pelanggaran</th>
                        <th class="py-3 px-4 text-center">Poin</th>
                        <th class="py-3 px-4">Pelapor</th>
                        <th class="py-3 px-4 text-center">Status Pembinaan</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($violations as $idx => $v)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 text-center text-xs font-semibold text-slate-400">
                                {{ $violations->firstItem() + $idx }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-800 text-xs">{{ $v->violation_date->translatedFormat('d M Y') }}</div>
                                <div class="text-[11px] text-slate-400">{{ substr($v->violation_time ?? '08:00', 0, 5) }} WIB</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <a href="{{ route('master.students.show', $v->student_id) }}" class="font-bold text-slate-800 hover:text-emerald-600 block text-xs sm:text-sm">
                                    {{ $v->student?->name }}
                                </a>
                                <span class="text-[11px] text-slate-400">NISN: {{ $v->student?->nisn }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2.5 py-0.5 rounded-lg bg-slate-100 text-slate-700 font-bold text-xs">
                                    {{ $v->student?->class?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 max-w-xs">
                                <div class="font-bold text-amber-800 text-xs">{{ $v->violationType?->name }}</div>
                                <div class="text-[11px] text-slate-500">{{ $v->violationType?->category?->name }}</div>
                                @if($v->proof_image)
                                    <span class="inline-flex items-center gap-1 text-[10px] text-emerald-600 font-semibold mt-0.5">
                                        <i data-lucide="image" class="w-3 h-3"></i> Ada Bukti Foto
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded-xl bg-amber-100 text-amber-800 font-extrabold text-xs">
                                    +{{ $v->points }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-600">
                                {{ $v->reporter?->name ?? 'Staf' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    @if($v->status == 'selesai') bg-emerald-100 text-emerald-800
                                    @elseif($v->status == 'dalam_pembinaan') bg-blue-100 text-blue-800
                                    @else bg-amber-100 text-amber-800
                                    @endif">
                                    {{ str_replace('_', ' ', $v->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('violations.show', $v->id) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-emerald-600 hover:text-white text-slate-700 font-bold text-xs rounded-lg transition-colors">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    <span>Tinjau</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400 text-sm">
                                Tidak ada data pelanggaran yang cocok dengan kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($violations->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $violations->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
