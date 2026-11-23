@extends('layouts.app')

@section('title', 'Catat Pelanggaran Siswa')
@section('page_title', 'Formulir Pencatatan Pelanggaran Siswa')

@section('content')
@php
    $initialStudent = $selectedStudentId ? $students->firstWhere('id', $selectedStudentId) : null;
    $initialClassId = $initialStudent ? $initialStudent->class_id : '';
@endphp

<div class="py-6 max-w-4xl mx-auto space-y-6" x-data="{
    selectedCategory: '',
    selectedType: '',
    points: 0,
    sanction: '',
    selectedClassFilter: '{{ $initialClassId }}',
    searchStudent: '',
    selectedStudentId: '{{ old('student_id', $selectedStudentId) }}',
    studentsList: [
        @foreach($students as $s)
            {
                id: '{{ $s->id }}',
                name: '{{ addslashes($s->name) }}',
                nisn: '{{ $s->nisn }}',
                class_id: '{{ $s->class_id }}',
                class_name: '{{ addslashes($s->class?->name ?? '-') }}',
                net_points: {{ $s->net_points }}
            },
        @endforeach
    ],
    typesByCategory: {
        @foreach($categories as $cat)
            '{{ $cat->id }}': [
                @foreach($cat->violationTypes as $t)
                    { id: '{{ $t->id }}', name: '{{ addslashes($t->name) }}', points: {{ $t->points }}, sanction: '{{ addslashes($t->default_sanction) }}' },
                @endforeach
            ],
        @endforeach
    },
    filteredStudents() {
        return this.studentsList.filter(s => {
            const matchClass = !this.selectedClassFilter || s.class_id === this.selectedClassFilter;
            const term = this.searchStudent.toLowerCase().trim();
            const matchSearch = !term || s.name.toLowerCase().includes(term) || s.nisn.includes(term);
            return matchClass && matchSearch;
        });
    },
    onClassFilterChange() {
        // Jika siswa yang sedang dipilih bukan dari kelas ini, reset pilihan siswa
        if (this.selectedStudentId) {
            const current = this.studentsList.find(s => s.id === this.selectedStudentId);
            if (current && this.selectedClassFilter && current.class_id !== this.selectedClassFilter) {
                this.selectedStudentId = '';
            }
        }
    },
    selectedStudentObj() {
        return this.studentsList.find(s => s.id === this.selectedStudentId);
    },
    updateTypes() {
        this.selectedType = '';
        this.points = 0;
        this.sanction = '';
    },
    onTypeChange() {
        const types = this.typesByCategory[this.selectedCategory] || [];
        const found = types.find(t => t.id == this.selectedType);
        if (found) {
            this.points = found.points;
            this.sanction = found.sanction;
        } else {
            this.points = 0;
            this.sanction = '';
        }
    }
}">

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <div class="p-6 border-b border-slate-100 bg-slate-50/70">
            <h3 class="font-extrabold text-slate-800 text-base flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-500"></i>
                <span>Input Laporan Pelanggaran Siswa</span>
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Catat kejadian pelanggaran tata tertib dan penambahan poin kedisiplinan murid.
            </p>
        </div>

        <form action="{{ route('violations.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
            @csrf

            <!-- ================= BAGIAN 1: FILTER KELAS & PEMILIHAN SISWA ================= -->
            <div class="p-5 bg-slate-50/80 rounded-2xl border border-slate-200/80 space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200/60">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="user-check" class="w-4 h-4 text-emerald-600"></i>
                        <span>Pilih Siswa yang Melanggar</span>
                    </h4>
                    <span class="text-[11px] font-semibold text-slate-500" x-text="filteredStudents().length + ' siswa sesuai filter'"></span>
                </div>

                <!-- Baris Filter Kelas & Input Pencarian Nama -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Filter Kelas -->
                    <div>
                        <label for="filter_class" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            1. Filter Berdasarkan Kelas
                        </label>
                        <select id="filter_class" x-model="selectedClassFilter" @change="onClassFilterChange()"
                                class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="">-- Tampilkan Semua Kelas --</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->major ?: $c->level }})</option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1">Pilih kelas untuk mempersempit daftar nama murid.</p>
                    </div>

                    <!-- Pencarian Nama Siswa / NISN -->
                    <div>
                        <label for="search_student" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            2. Cari Nama / NISN Murid
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </div>
                            <input type="text" id="search_student" x-model="searchStudent"
                                   placeholder="Ketik nama atau NISN murid..."
                                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <button type="button" x-show="searchStudent" @click="searchStudent = ''" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Pencarian instan langsung menyaring daftar murid di bawah.</p>
                    </div>
                </div>

                <!-- Dropdown Pilihan Nama Siswa Terkait -->
                <div>
                    <label for="student_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        3. Pilih Nama Siswa Terkait <span class="text-rose-500">*</span>
                    </label>
                    <select name="student_id" id="student_id" x-model="selectedStudentId" required
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="">-- Pilih Siswa dari Daftar --</option>
                        <template x-for="st in filteredStudents()" :key="st.id">
                            <option :value="st.id" x-text="st.name + ' • ' + st.class_name + ' (NISN: ' + st.nisn + ') - Poin Aktif: ' + st.net_points"></option>
                        </template>
                    </select>
                </div>

                <!-- Highlight Card Siswa Terpilih -->
                <template x-if="selectedStudentObj()">
                    <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white font-black flex items-center justify-center text-sm shadow-xs"
                                 x-text="selectedStudentObj().name.substring(0, 2).toUpperCase()"></div>
                            <div>
                                <p class="font-extrabold text-slate-800 text-sm" x-text="selectedStudentObj().name"></p>
                                <p class="text-slate-600 mt-0.5">
                                    Kelas: <strong class="text-slate-800" x-text="selectedStudentObj().class_name"></strong> &bull; 
                                    NISN: <span class="font-mono text-slate-700" x-text="selectedStudentObj().nisn"></span>
                                </p>
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="inline-block px-3 py-1 rounded-xl text-xs font-black"
                                  :class="selectedStudentObj().net_points >= 30 ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800'"
                                  x-text="'Poin Aktif: ' + selectedStudentObj().net_points + ' Poin'">
                            </span>
                        </div>
                    </div>
                </template>
            </div>

            <!-- ================= BAGIAN 2: KATEGORI & JENIS PELANGGARAN ================= -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kategori Pelanggaran <span class="text-rose-500">*</span>
                    </label>
                    <select id="category_id" x-model="selectedCategory" @change="updateTypes()" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="violation_type_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Jenis Pelanggaran <span class="text-rose-500">*</span>
                    </label>
                    <select name="violation_type_id" id="violation_type_id" x-model="selectedType" @change="onTypeChange()" required
                            :disabled="!selectedCategory"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none disabled:bg-slate-100 disabled:text-slate-400">
                        <option value="">-- Pilih Jenis Pelanggaran --</option>
                        <template x-for="item in (typesByCategory[selectedCategory] || [])" :key="item.id">
                            <option :value="item.id" x-text="item.name + ' (+' + item.points + ' Poin)'"></option>
                        </template>
                    </select>
                </div>
            </div>

            <!-- Dynamic Point & Sanction Preview -->
            <div x-show="points > 0" x-cloak class="p-4 rounded-xl bg-amber-50 border border-amber-200 flex items-start gap-4">
                <div class="px-3 py-1.5 bg-amber-500 text-slate-950 font-black text-sm rounded-lg shrink-0">
                    +<span x-text="points"></span> Poin
                </div>
                <div class="text-xs">
                    <p class="font-bold text-amber-900">Sanksi Standar Sesuai Aturan:</p>
                    <p class="text-amber-800 mt-0.5" x-text="sanction || 'Teguran & pembinaan sesuai tata tertib.'"></p>
                </div>
            </div>

            <!-- Tanggal & Jam Kejadian -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="violation_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tanggal Kejadian <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="violation_date" id="violation_date" required value="{{ old('violation_date', date('Y-m-d')) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label for="violation_time" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Waktu / Jam Kejadian
                    </label>
                    <input type="time" name="violation_time" id="violation_time" value="{{ old('violation_time', date('H:i')) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <!-- Catatan Kronologi -->
            <div>
                <label for="notes" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Kronologi / Keterangan Pelanggaran
                </label>
                <textarea name="notes" id="notes" rows="3"
                          placeholder="Jelaskan secara singkat kronologi kejadian, saksi, atau lokasi terjadinya pelanggaran..."
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('notes') }}</textarea>
            </div>

            <!-- Upload Foto Bukti -->
            <div>
                <label for="proof_image" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Unggah Foto Bukti Pelanggaran (Opsional)
                </label>
                <input type="file" name="proof_image" id="proof_image" accept="image/*"
                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                <p class="text-[11px] text-slate-400 mt-1">Format gambar: JPG, PNG, WEBP (Maksimal 3MB). Foto barang bukti atau dokumentasi kejadian.</p>
            </div>

            <!-- Form Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('violations.index') }}" class="px-5 py-2.5 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-emerald-700/30 transition-all flex items-center gap-2">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Simpan & Catat Poin</span>
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
