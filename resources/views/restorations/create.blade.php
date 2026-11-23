@extends('layouts.app')

@section('title', 'Catat Pemutihan Poin')
@section('page_title', 'Formulir Pemutihan / Pengurangan Poin Siswa')

@section('content')
<div class="py-6 max-w-4xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <div class="p-6 border-b border-slate-100 bg-slate-50/70">
            <h3 class="font-extrabold text-slate-800 text-base flex items-center gap-2">
                <i data-lucide="sparkles" class="w-5 h-5 text-teal-600"></i>
                <span>Input Pemutihan / Pengurangan Poin Pelanggaran</span>
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Berikan pengurangan poin bagi siswa yang telah melaksanakan konseling BK, tugas sosial, atau berprestasi.
            </p>
        </div>

        <form action="{{ route('restorations.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf

            <!-- Pilih Siswa -->
            <div>
                <label for="student_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Siswa <span class="text-rose-500">*</span>
                </label>
                <select name="student_id" id="student_id" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <option value="">-- Pilih Siswa dengan Poin Aktif --</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}" {{ (old('student_id', $selectedStudentId) == $s->id) ? 'selected' : '' }}>
                            {{ $s->name }} &bull; {{ $s->class?->name }} (Poin Aktif: {{ $s->net_points }} Poin)
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-500 mt-1">Hanya siswa yang memiliki poin pelanggaran aktif yang dapat diberikan pemutihan.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="restored_points" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Jumlah Poin yang Dikurangi/Diputihkan <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="restored_points" id="restored_points" required min="1" max="100" value="{{ old('restored_points', 5) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-teal-700 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <p class="text-[11px] text-slate-400 mt-1">Contoh: 5, 10, atau 20 poin.</p>
                </div>

                <div>
                    <label for="restoration_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tanggal Pemutihan <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="restoration_date" id="restoration_date" required value="{{ old('restoration_date', date('Y-m-d')) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <!-- Alasan Pemutihan -->
            <div>
                <label for="reason" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Alasan / Kategori Pemutihan <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="reason" id="reason" required value="{{ old('reason', 'Konseling rutin BK & komitmen disiplin') }}"
                       placeholder="Contoh: Konseling rutin, tugas sosial kebersihan sekolah, prestasi lomba, dll."
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <!-- Catatan Tambahan -->
            <div>
                <label for="notes" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Catatan Perkembangan Siswa
                </label>
                <textarea name="notes" id="notes" rows="3"
                          placeholder="Jelaskan perubahan perilaku positif siswa setelah sesi konseling..."
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('notes') }}</textarea>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('restorations.index') }}" class="px-5 py-2.5 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-teal-600/30 transition-all flex items-center gap-2">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Simpan Pemutihan Poin</span>
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
