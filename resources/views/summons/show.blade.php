@extends('layouts.app')

@section('title', 'Detail Surat Panggilan Orang Tua')
@section('page_title', 'Detail Surat Panggilan Orang Tua')

@section('content')
<div class="py-6 max-w-4xl mx-auto space-y-6">

    <!-- Top Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <a href="{{ route('summons.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Surat</span>
        </a>

        <div class="flex items-center gap-2">
            @if($waLink)
                <a href="{{ $waLink }}" target="_blank"
                   class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                    <span>Kirim Pesan WhatsApp ke Orang Tua</span>
                </a>
            @endif

            <a href="{{ route('summons.print', $summons->id) }}" target="_blank"
               class="px-4 py-2 bg-navy-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Cetak Surat Resmi</span>
            </a>
        </div>
    </div>

    <!-- Letter Details Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <div class="p-6 border-b border-slate-100 bg-slate-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-rose-100 text-rose-800 mb-1">
                    {{ $summons->summons_type }}
                </span>
                <h3 class="text-lg font-black text-slate-800 font-mono">{{ $summons->letter_number }}</h3>
            </div>

            <div class="text-right">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                    @if($summons->status == 'selesai') bg-emerald-100 text-emerald-800
                    @elseif($summons->status == 'hadir') bg-blue-100 text-blue-800
                    @elseif($summons->status == 'tidak_hadir') bg-rose-100 text-rose-800
                    @else bg-amber-100 text-amber-800
                    @endif">
                    Status: {{ str_replace('_', ' ', $summons->status) }}
                </span>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            
            <!-- Student Information -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-slate-400 font-medium">Nama Siswa:</span>
                    <p class="font-extrabold text-slate-800 text-sm mt-0.5">{{ $summons->student?->name }}</p>
                    <p class="text-slate-500 mt-0.5">NISN: {{ $summons->student?->nisn }} &bull; Kelas: {{ $summons->student?->class?->name }}</p>
                </div>

                <div>
                    <span class="text-slate-400 font-medium">Orang Tua / Wali:</span>
                    <p class="font-bold text-slate-800 mt-0.5">{{ $summons->student?->parent_name ?: 'Orang Tua / Wali' }}</p>
                    <p class="text-slate-500 mt-0.5">No WA: {{ $summons->student?->parent_phone ?: 'Belum diisi' }}</p>
                </div>
            </div>

            <!-- Schedule Info -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="text-slate-400 font-medium">Hari / Tanggal Menghadap:</span>
                    <p class="font-extrabold text-slate-800 text-sm mt-0.5">{{ $summons->summons_date->translatedFormat('l, d F Y') }}</p>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="text-slate-400 font-medium">Waktu Kehadiran:</span>
                    <p class="font-extrabold text-slate-800 text-sm mt-0.5">Pukul {{ substr($summons->summons_time, 0, 5) }} WIB</p>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="text-slate-400 font-medium">Tempat Pertemuan:</span>
                    <p class="font-extrabold text-slate-800 text-sm mt-0.5">{{ $summons->meeting_place }}</p>
                </div>
            </div>

            <!-- Reason -->
            <div>
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alasan Pemanggilan:</h4>
                <div class="p-4 rounded-xl bg-slate-50 text-slate-700 text-sm leading-relaxed border border-slate-100">
                    {{ $summons->reason }}
                </div>
            </div>

            <!-- Existing Meeting Result -->
            @if($summons->meeting_result)
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs">
                    <p class="font-bold text-emerald-900">Hasil Pertemuan / Kesepakatan Bersama Orang Tua:</p>
                    <p class="text-emerald-800 mt-1 leading-relaxed">{{ $summons->meeting_result }}</p>
                </div>
            @endif

        </div>

        <!-- Meeting Result Update Form -->
        <div class="p-6 bg-slate-50 border-t border-slate-200">
            <h4 class="font-bold text-slate-800 text-sm mb-1 flex items-center gap-2">
                <i data-lucide="clipboard-check" class="w-4 h-4 text-emerald-600"></i>
                <span>Catat Hasil Pertemuan & Kehadiran Orang Tua</span>
            </h4>
            <p class="text-xs text-slate-500 mb-4">Perbarui catatan setelah orang tua siswa hadir memenuhi panggilan ke sekolah.</p>

            <form action="{{ route('summons.update_result', $summons->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status Kehadiran</label>
                        <select name="status" id="status" required
                                class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="diterbitkan" {{ $summons->status == 'diterbitkan' ? 'selected' : '' }}>Diterbitkan (Belum Hadir)</option>
                            <option value="hadir" {{ $summons->status == 'hadir' ? 'selected' : '' }}>Orang Tua Hadir</option>
                            <option value="dijadwalkan_ulang" {{ $summons->status == 'dijadwalkan_ulang' ? 'selected' : '' }}>Dijadwalkan Ulang</option>
                            <option value="tidak_hadir" {{ $summons->status == 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir Tanpa Kabar</option>
                            <option value="selesai" {{ $summons->status == 'selesai' ? 'selected' : '' }}>Selesai / Dituntaskan</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="meeting_result" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Hasil Pertemuan / Pernyataan Komitmen</label>
                        <input type="text" name="meeting_result" id="meeting_result" value="{{ old('meeting_result', $summons->meeting_result) }}"
                               placeholder="Contoh: Orang tua hadir dan menandatangani surat perjanjian bermaterai..."
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-5 py-2 bg-navy-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition-colors flex items-center gap-1.5">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                        <span>Simpan Hasil Pertemuan</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection
