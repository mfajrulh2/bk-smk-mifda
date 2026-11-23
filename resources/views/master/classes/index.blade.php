@extends('layouts.app')

@section('title', 'Data Rombongan Belajar (Kelas)')
@section('page_title', 'Master Data Kelas & Wali Kelas')

@section('content')
<div class="py-6 space-y-6" x-data="{ createModal: false, editModal: false, editData: {} }">

    <!-- Top Action Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="font-extrabold text-slate-800 text-base">Rombongan Belajar (Kelas)</h3>
            <p class="text-xs text-slate-500">Kelola daftar kelas, tingkat, kompetensi keahlian, dan penugasan wali kelas</p>
        </div>

        <button type="button" @click="createModal = true"
                class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-700/30 transition-all flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>+ Tambah Kelas Baru</span>
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Nama Kelas</th>
                        <th class="py-3 px-4 text-center">Tingkat</th>
                        <th class="py-3 px-4">Jurusan / Peminatan</th>
                        <th class="py-3 px-4">Wali Kelas</th>
                        <th class="py-3 px-4 text-center">Jumlah Siswa</th>
                        <th class="py-3 px-4 text-center">Tahun Ajaran</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($classes as $idx => $c)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 text-center text-xs font-semibold text-slate-400">
                                {{ $idx + 1 }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-800">
                                {{ $c->name }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold">
                                    Kelas {{ $c->level }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-600">
                                {{ $c->major ?: 'Umum' }}
                            </td>
                            <td class="py-3.5 px-4 text-xs">
                                <div class="font-semibold text-slate-800">{{ $c->homeroomTeacher?->name ?? 'Belum ditentukan' }}</div>
                                <div class="text-[10px] text-slate-400">NIP: {{ $c->homeroomTeacher?->identifier_number ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('master.students', ['class_id' => $c->id]) }}" class="px-2.5 py-1 bg-emerald-50 text-emerald-700 font-extrabold text-xs rounded-lg hover:bg-emerald-100 transition-colors">
                                    {{ $c->students_count }} Siswa
                                </a>
                            </td>
                            <td class="py-3.5 px-4 text-center text-xs text-slate-500">
                                {{ $c->academic_year }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center gap-1">
                                    <button type="button" @click="editData = { id: {{ $c->id }}, name: '{{ addslashes($c->name) }}', level: '{{ $c->level }}', major: '{{ addslashes($c->major) }}', homeroom_teacher_id: '{{ $c->homeroom_teacher_id }}' }; editModal = true;"
                                            class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400 text-sm">
                                Belum ada kelas yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Create Class -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="createModal = false" class="bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-md w-full p-6 space-y-4">
            <h3 class="font-extrabold text-slate-800 text-base">Tambah Rombongan Belajar (Kelas)</h3>

            <form action="{{ route('master.classes.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Kelas</label>
                    <input type="text" name="name" required placeholder="Contoh: X RPL 2" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-1 focus:ring-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tingkat</label>
                        <select name="level" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                            <option value="X">Kelas X (10)</option>
                            <option value="XI">Kelas XI (11)</option>
                            <option value="XII">Kelas XII (12)</option>
                            <option value="VII">Kelas VII (7)</option>
                            <option value="VIII">Kelas VIII (8)</option>
                            <option value="IX">Kelas IX (9)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jurusan / Program</label>
                        <input type="text" name="major" placeholder="RPL / TKJ / MIPA" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Wali Kelas</label>
                    <select name="homeroom_teacher_id" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                        <option value="">-- Pilih Guru / Wali Kelas --</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->name }} (NIP: {{ $t->identifier_number ?: '-' }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl">Simpan Kelas</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Class -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="editModal = false" class="bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-md w-full p-6 space-y-4">
            <h3 class="font-extrabold text-slate-800 text-base">Ubah Data Kelas</h3>

            <form :action="'{{ url('master/classes') }}/' + editData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Kelas</label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tingkat</label>
                        <select name="level" x-model="editData.level" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                            <option value="X">Kelas X (10)</option>
                            <option value="XI">Kelas XI (11)</option>
                            <option value="XII">Kelas XII (12)</option>
                            <option value="VII">Kelas VII (7)</option>
                            <option value="VIII">Kelas VIII (8)</option>
                            <option value="IX">Kelas IX (9)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jurusan / Program</label>
                        <input type="text" name="major" x-model="editData.major" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Wali Kelas</label>
                    <select name="homeroom_teacher_id" x-model="editData.homeroom_teacher_id" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                        <option value="">-- Pilih Guru / Wali Kelas --</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="editModal = false" class="px-4 py-2 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
