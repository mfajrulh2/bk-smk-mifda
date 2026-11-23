@extends('layouts.app')

@section('title', 'Terbitkan Surat Panggilan Orang Tua')
@section('page_title', 'Formulir Surat Panggilan Orang Tua')

@section('content')
<div class="py-6 max-w-4xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <div class="p-6 border-b border-slate-100 bg-slate-50/70">
            <h3 class="font-extrabold text-slate-800 text-base flex items-center gap-2">
                <i data-lucide="mail-warning" class="w-5 h-5 text-rose-600"></i>
                <span>Penerbitan Surat Panggilan Resmi Orang Tua / Wali Siswa</span>
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Surat ini akan dicatat dalam database resmi kedisiplinan dan dapat dicetak sebagai dokumen berkop resmi sekolah.
            </p>
        </div>

        <form action="{{ route('summons.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf

            <!-- Pilih Siswa -->
            <div>
                <label for="student_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Siswa <span class="text-rose-500">*</span>
                </label>
                <select name="student_id" id="student_id" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}" {{ (old('student_id', $selectedStudent?->id) == $s->id) ? 'selected' : '' }}>
                            {{ $s->name }} &bull; {{ $s->class?->name }} (Poin: {{ $s->net_points }} Poin) - Ortu: {{ $s->parent_name ?: 'Bapak/Ibu Wali' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Nomor Surat & Jenis Surat -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="letter_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nomor Surat Resmi <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="letter_number" id="letter_number" required value="{{ old('letter_number', $autoLetterNumber) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono font-bold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <p class="text-[11px] text-slate-400 mt-1">Format penomoran otomatis sesuai agenda sekolah.</p>
                </div>

                <div>
                    <label for="summons_type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Jenis Panggilan <span class="text-rose-500">*</span>
                    </label>
                    <select name="summons_type" id="summons_type" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <option value="Surat Panggilan Orang Tua I (Tahap 1)">Surat Panggilan Orang Tua I (Tahap 1)</option>
                        <option value="Surat Panggilan Orang Tua II (Tahap 2)">Surat Panggilan Orang Tua II (Tahap 2)</option>
                        <option value="Surat Panggilan Orang Tua III (Panggilan Terakhir / Sidang)">Surat Panggilan Orang Tua III (Panggilan Terakhir / Sidang)</option>
                        <option value="Surat Undangan Bimbingan Konseling Khusus">Surat Undangan Bimbingan Konseling Khusus</option>
                    </select>
                </div>
            </div>

            <!-- Jadwal Kehadiran -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="summons_date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tanggal Menghadap <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="summons_date" id="summons_date" required value="{{ old('summons_date', date('Y-m-d', strtotime('+2 days'))) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label for="summons_time" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Waktu / Pukul <span class="text-rose-500">*</span>
                    </label>
                    <input type="time" name="summons_time" id="summons_time" required value="{{ old('summons_time', '08:30') }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label for="meeting_place" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tempat Pertemuan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="meeting_place" id="meeting_place" required value="{{ old('meeting_place', 'Ruang Bimbingan & Konseling (BK)') }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <!-- Alasan Pemanggilan -->
            <div>
                <label for="reason" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Alasan / Perihal Pemanggilan <span class="text-rose-500">*</span>
                </label>
                <textarea name="reason" id="reason" rows="3" required
                          placeholder="Jelaskan alasan pemanggilan orang tua, akumulasi poin, atau pelanggaran yang memerlukan penanganan bersama..."
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('reason', 'Akumulasi poin pelanggaran siswa telah melampaui batas toleransi tata tertib sekolah. Diperlukan klarifikasi dan koordinasi pembinaan kedisiplinan bersama orang tua.') }}</textarea>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('summons.index') }}" class="px-5 py-2.5 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-rose-600/30 transition-all flex items-center gap-2">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Terbitkan Surat Panggilan</span>
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
