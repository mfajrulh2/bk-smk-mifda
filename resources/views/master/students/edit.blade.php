@extends('layouts.app')

@section('title', 'Ubah Data Siswa')
@section('page_title', 'Perbarui Data Siswa')

@section('content')
<div class="py-6 max-w-3xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <div class="p-6 border-b border-slate-100 bg-slate-50/70">
            <h3 class="font-extrabold text-slate-800 text-base flex items-center gap-2">
                <i data-lucide="edit-2" class="w-5 h-5 text-blue-600"></i>
                <span>Edit Biodata Siswa: {{ $student->name }}</span>
            </h3>
        </div>

        <form action="{{ route('master.students.update', $student->id) }}" method="POST" class="p-6 sm:p-8 space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="nisn" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        NISN <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nisn" id="nisn" required value="{{ old('nisn', $student->nisn) }}" maxlength="20"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label for="nis" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        NIS
                    </label>
                    <input type="text" name="nis" id="nis" value="{{ old('nis', $student->nis) }}" maxlength="20"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Lengkap Siswa <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" required value="{{ old('name', $student->name) }}"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="gender" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Jenis Kelamin <span class="text-rose-500">*</span>
                    </label>
                    <select name="gender" id="gender" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                </div>

                <div>
                    <label for="class_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kelas <span class="text-rose-500">*</span>
                    </label>
                    <select name="class_id" id="class_id" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ old('class_id', $student->class_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Status Kesiswaan <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" id="status" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="aktif" {{ old('status', $student->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="lulus" {{ old('status', $student->status) == 'lulus' ? 'selected' : '' }}>Lulus</option>
                        <option value="mutasi" {{ old('status', $student->status) == 'mutasi' ? 'selected' : '' }}>Mutasi (Pindah)</option>
                        <option value="drop_out" {{ old('status', $student->status) == 'drop_out' ? 'selected' : '' }}>Drop Out</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="parent_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Orang Tua / Wali
                    </label>
                    <input type="text" name="parent_name" id="parent_name" value="{{ old('parent_name', $student->parent_name) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label for="parent_phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        No. WhatsApp Orang Tua
                    </label>
                    <input type="text" name="parent_phone" id="parent_phone" value="{{ old('parent_phone', $student->parent_phone) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label for="address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Alamat Domisili
                </label>
                <textarea name="address" id="address" rows="2"
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('address', $student->address) }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <button type="button" onclick="if(confirm('Yakin ingin menghapus data siswa ini? Semua catatan pelanggaran dan presensi terkait akan ikut terhapus.')) document.getElementById('delete-student-form').submit();"
                        class="px-4 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-xl transition-colors">
                    Hapus Siswa
                </button>

                <div class="flex items-center gap-3">
                    <a href="{{ route('master.students') }}" class="px-5 py-2.5 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-emerald-700/30 transition-all flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </div>

        </form>

        <form id="delete-student-form" action="{{ route('master.students.destroy', $student->id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

    </div>

</div>
@endsection
