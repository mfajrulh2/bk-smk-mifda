@extends('layouts.app')

@section('title', 'Tambah Siswa Baru')
@section('page_title', 'Formulir Tambah Siswa Baru')

@section('content')
<div class="py-6 max-w-3xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <div class="p-6 border-b border-slate-100 bg-slate-50/70">
            <h3 class="font-extrabold text-slate-800 text-base flex items-center gap-2">
                <i data-lucide="user-plus" class="w-5 h-5 text-emerald-600"></i>
                <span>Data Induk Siswa Baru</span>
            </h3>
            <p class="text-xs text-slate-500 mt-1">Masukkan biodata siswa, rombel, dan kontak orang tua / wali untuk notifikasi WhatsApp.</p>
        </div>

        <form action="{{ route('master.students.store') }}" method="POST" class="p-6 sm:p-8 space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="nisn" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        NISN (Nomor Induk Siswa Nasional) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nisn" id="nisn" required value="{{ old('nisn') }}" maxlength="20"
                           placeholder="Contoh: 0081234567"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label for="nis" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        NIS (Nomor Induk Sekolah)
                    </label>
                    <input type="text" name="nis" id="nis" value="{{ old('nis') }}" maxlength="20"
                           placeholder="Contoh: 26001"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Lengkap Siswa <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}"
                       placeholder="Nama lengkap sesuai akta / ijazah"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="gender" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Jenis Kelamin <span class="text-rose-500">*</span>
                    </label>
                    <select name="gender" id="gender" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                </div>

                <div>
                    <label for="class_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Rombongan Belajar (Kelas) <span class="text-rose-500">*</span>
                    </label>
                    <select name="class_id" id="class_id" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="parent_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Orang Tua / Wali Murid
                    </label>
                    <input type="text" name="parent_name" id="parent_name" value="{{ old('parent_name') }}"
                           placeholder="Nama ayah / ibu / wali"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label for="parent_phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        No. WhatsApp Orang Tua / Wali
                    </label>
                    <input type="text" name="parent_phone" id="parent_phone" value="{{ old('parent_phone') }}"
                           placeholder="Contoh: 081234567890"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <p class="text-[11px] text-slate-400 mt-1">Digunakan untuk pengiriman notifikasi presensi & panggilan otomatis.</p>
                </div>
            </div>

            <div>
                <label for="address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Alamat Tempat Tinggal
                </label>
                <textarea name="address" id="address" rows="2"
                          placeholder="Alamat domisili lengkap..."
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('address') }}</textarea>
            </div>

            <!-- Checkbox create user portal -->
            <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="create_user_account" value="1" checked
                           class="mt-0.5 rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500">
                    <div>
                        <p class="text-xs font-bold text-emerald-950">Buat Akun Portal Siswa / Orang Tua Otomatis</p>
                        <p class="text-[11px] text-emerald-800 mt-0.5">
                            Siswa/Orang tua dapat login ke sistem menggunakan Username = NISN dan Kata Sandi awal = NISN.
                        </p>
                    </div>
                </label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('master.students') }}" class="px-5 py-2.5 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-emerald-700/30 transition-all flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Siswa</span>
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
