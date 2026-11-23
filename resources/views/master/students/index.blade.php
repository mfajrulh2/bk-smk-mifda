@extends('layouts.app')

@section('title', 'Data Siswa')
@section('page_title', 'Master Data Siswa')

@section('content')
<div class="py-6 space-y-6" x-data="{ importModalOpen: false }">

    <!-- Top Action & Toolbar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-extrabold text-slate-800 text-base">Database Induk Siswa</h3>
                <p class="text-xs text-slate-500">Kelola informasi peserta didik, nomor WhatsApp orang tua, dan histori disiplin</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <!-- Download Template CSV -->
                <a href="{{ route('master.students.template') }}" 
                   class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors flex items-center gap-1.5">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span>Template Excel</span>
                </a>

                <!-- Import Button -->
                <button type="button" @click="importModalOpen = true"
                        class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                    <i data-lucide="file-up" class="w-4 h-4"></i>
                    <span>Impor Siswa (CSV/Excel)</span>
                </button>

                <!-- Add Student Button -->
                <a href="{{ route('master.students.create') }}" 
                   class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-700/30 transition-all flex items-center gap-1.5">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    <span>+ Tambah Siswa</span>
                </a>
            </div>
        </div>

        <!-- Filter & Search -->
        <form action="{{ route('master.students') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-3 border-t border-slate-100 items-end">
            <div class="sm:col-span-6">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama siswa, NISN, atau NIS..."
                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-1 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="sm:col-span-4">
                <select name="class_id" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-1 focus:ring-emerald-500 focus:outline-none">
                    <option value="">Semua Rombongan Belajar (Kelas)</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <button type="submit" class="w-full py-2 bg-navy-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition-colors flex items-center justify-center gap-1.5">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>
            </div>
        </form>

    </div>

    <!-- Student Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4 text-center">NISN / NIS</th>
                        <th class="py-3 px-4 text-center">L/P</th>
                        <th class="py-3 px-4">Kelas</th>
                        <th class="py-3 px-4">Orang Tua & No WA</th>
                        <th class="py-3 px-4 text-center">Poin Disiplin</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $idx => $s)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 text-center text-xs font-semibold text-slate-400">
                                {{ $students->firstItem() + $idx }}
                            </td>
                            <td class="py-3.5 px-4">
                                <a href="{{ route('master.students.show', $s->id) }}" class="font-bold text-slate-800 hover:text-emerald-600 block text-xs sm:text-sm">
                                    {{ $s->name }}
                                </a>
                                <span class="text-[11px] text-slate-400">Status: {{ ucfirst($s->status) }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center text-xs text-slate-600 font-mono">
                                <div>{{ $s->nisn }}</div>
                                <div class="text-[10px] text-slate-400">{{ $s->nis ?: '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="text-xs font-bold px-2 py-0.5 rounded {{ $s->gender == 'L' ? 'bg-sky-50 text-sky-800' : 'bg-pink-50 text-pink-800' }}">
                                    {{ $s->gender }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-xs font-semibold text-slate-700">
                                {{ $s->class?->name ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-xs">
                                <div class="font-semibold text-slate-800">{{ $s->parent_name ?: 'Bapak/Ibu Wali' }}</div>
                                @if($s->parent_phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $s->parent_phone) }}" target="_blank"
                                       class="inline-flex items-center gap-1 text-[11px] text-emerald-600 font-semibold hover:underline mt-0.5">
                                        <i data-lucide="phone" class="w-3 h-3"></i>
                                        <span>{{ $s->parent_phone }}</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-[11px]">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @php $net = $s->net_points; @endphp
                                <span class="inline-block px-2.5 py-1 rounded-xl text-xs font-extrabold
                                    {{ $net == 0 ? 'bg-emerald-100 text-emerald-800' : ($net < 30 ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                    {{ $net }} Poin
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('master.students.show', $s->id) }}" class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Profil Lengkap">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('master.students.edit', $s->id) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Data">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400 text-sm">
                                Tidak ada data siswa yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $students->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Import CSV / Excel -->
    <div x-show="importModalOpen" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="importModalOpen = false"
             class="bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-lg w-full p-6 space-y-4">
            
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-extrabold text-slate-800 text-base flex items-center gap-2">
                    <i data-lucide="file-up" class="w-5 h-5 text-blue-600"></i>
                    <span>Impor Data Siswa Sekaligus</span>
                </h3>
                <button @click="importModalOpen = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <p class="text-xs text-slate-500 leading-relaxed">
                Unggah file berformat <strong>.CSV</strong> sesuai dengan struktur template standar. Jika siswa dengan NISN sama sudah ada, datanya akan diperbarui secara otomatis.
            </p>

            <form action="{{ route('master.students.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih File CSV Siswa</label>
                    <input type="file" name="csv_file" required accept=".csv,.txt"
                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                </div>

                <div class="p-3 bg-slate-50 rounded-xl text-[11px] text-slate-500 flex items-center justify-between">
                    <span>Belum memiliki format template?</span>
                    <a href="{{ route('master.students.template') }}" class="font-bold text-blue-600 hover:underline">
                        Unduh Template &rarr;
                    </a>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="importModalOpen = false" class="px-4 py-2 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition-colors">
                        Mulai Proses Impor
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
