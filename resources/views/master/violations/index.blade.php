@extends('layouts.app')

@section('title', 'Master Jenis & Poin Pelanggaran')
@section('page_title', 'Master Jenis & Bobot Poin Pelanggaran')

@section('content')
<div class="py-6 space-y-6" x-data="{ createModal: false, editModal: false, editData: {} }">

    <!-- Top Action Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="font-extrabold text-slate-800 text-base">Katalog Aturan & Bobot Poin Pelanggaran</h3>
            <p class="text-xs text-slate-500">Sesuaikan daftar pelanggaran, bobot poin minus, serta sanksi standar sekolah</p>
        </div>

        <button type="button" @click="createModal = true"
                class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl shadow-md shadow-amber-500/20 transition-all flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>+ Tambah Aturan Pelanggaran</span>
        </button>
    </div>

    <!-- Category Groups -->
    <div class="space-y-6">
        @foreach($categories as $cat)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-4 sm:p-5 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h4 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            <span>{{ $cat->name }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-slate-200 text-slate-700 font-bold">
                                {{ $cat->violationTypes->count() }} Jenis
                            </span>
                        </h4>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $cat->description }}</p>
                    </div>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($cat->violationTypes as $t)
                        <div class="p-4 hover:bg-slate-50/80 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <div class="space-y-1">
                                <p class="font-bold text-slate-800 text-sm">{{ $t->name }}</p>
                                <p class="text-slate-500">Sanksi standar: <span class="font-medium text-slate-700">{{ $t->default_sanction ?: 'Teguran lisan & pembinaan' }}</span></p>
                            </div>

                            <div class="flex items-center gap-4">
                                <span class="px-3 py-1 bg-amber-100 text-amber-800 font-black text-xs rounded-xl">
                                    +{{ $t->points }} Poin
                                </span>
                                <div class="inline-flex items-center gap-1">
                                    <button type="button" @click="editData = { id: {{ $t->id }}, name: '{{ addslashes($t->name) }}', points: {{ $t->points }}, default_sanction: '{{ addslashes($t->default_sanction) }}' }; editModal = true;"
                                            class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </button>
                                    <form action="{{ route('master.violation_types.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus jenis pelanggaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-400 text-xs">Belum ada pelanggaran di kategori ini.</div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Create -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="createModal = false" class="bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-md w-full p-6 space-y-4">
            <h3 class="font-extrabold text-slate-800 text-base">Tambah Jenis Pelanggaran Baru</h3>

            <form action="{{ route('master.violation_types.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kategori</label>
                    <select name="category_id" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Jenis Pelanggaran</label>
                    <input type="text" name="name" required placeholder="Contoh: Merokok di lingkungan sekolah" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Bobot Poin</label>
                    <input type="number" name="points" required min="1" max="100" value="5" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-amber-700">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Sanksi Standar</label>
                    <input type="text" name="default_sanction" placeholder="Teguran lisan & surat pernyataan" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl">Simpan Aturan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="editModal = false" class="bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-md w-full p-6 space-y-4">
            <h3 class="font-extrabold text-slate-800 text-base">Ubah Jenis Pelanggaran</h3>

            <form :action="'{{ url('master/violation-types') }}/' + editData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Jenis Pelanggaran</label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Bobot Poin</label>
                    <input type="number" name="points" x-model="editData.points" required min="1" max="100" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-amber-700">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Sanksi Standar</label>
                    <input type="text" name="default_sanction" x-model="editData.default_sanction" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="editModal = false" class="px-4 py-2 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
