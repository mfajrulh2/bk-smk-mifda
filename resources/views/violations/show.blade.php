@extends('layouts.app')

@section('title', 'Detail Kasus Pelanggaran')
@section('page_title', 'Detail Laporan Pelanggaran')

@section('content')
<div class="py-6 max-w-4xl mx-auto space-y-6">

    <!-- Back Button & Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('violations.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Pelanggaran</span>
        </a>

        @if($waLink)
            <a href="{{ $waLink }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-600/30 transition-all">
                <i data-lucide="message-circle" class="w-4 h-4"></i>
                <span>Kirim Pemberitahuan WhatsApp ke Ortu</span>
            </a>
        @endif
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <!-- Header Info -->
        <div class="p-6 border-b border-slate-100 bg-slate-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold">
                    <i data-lucide="alert-octagon" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">{{ $violation->violationType?->name }}</h3>
                    <p class="text-xs text-slate-500">
                        Kategori: <span class="font-bold text-slate-700">{{ $violation->violationType?->category?->name }}</span> &bull; 
                        Tanggal: {{ $violation->violation_date->translatedFormat('l, d F Y') }} ({{ substr($violation->violation_time ?? '08:00', 0, 5) }} WIB)
                    </p>
                </div>
            </div>

            <div class="text-right">
                <span class="inline-block px-3.5 py-1.5 bg-amber-500 text-slate-950 font-black text-sm rounded-xl">
                    +{{ $violation->points }} Poin
                </span>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            
            <!-- Student Profile Summary -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-sm">
                        {{ substr($violation->student?->name ?? 'S', 0, 2) }}
                    </div>
                    <div>
                        <a href="{{ route('master.students.show', $violation->student_id) }}" class="font-bold text-slate-800 text-sm hover:text-emerald-600">
                            {{ $violation->student?->name }}
                        </a>
                        <p class="text-xs text-slate-500">
                            Kelas: <span class="font-semibold text-slate-700">{{ $violation->student?->class?->name }}</span> &bull; 
                            NISN: {{ $violation->student?->nisn }} &bull; 
                            No HP Ortu: <span class="font-semibold text-slate-700">{{ $violation->student?->parent_phone ?? 'Belum ada' }}</span>
                        </p>
                    </div>
                </div>

                <div class="text-right">
                    <p class="text-[11px] font-semibold text-slate-400">Total Akumulasi Poin Aktif:</p>
                    <p class="text-base font-black text-rose-600">{{ $violation->student?->net_points }} Poin</p>
                </div>
            </div>

            <!-- Chronology & Notes -->
            <div>
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kronologi / Catatan Kejadian:</h4>
                <div class="p-4 rounded-xl bg-slate-50 text-slate-700 text-sm leading-relaxed border border-slate-100">
                    {{ $violation->notes ?: 'Tidak ada catatan kronologi khusus yang dicantumkan.' }}
                </div>
            </div>

            <!-- Proof Photo -->
            @if($violation->proof_image)
                <div>
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Foto Barang Bukti / Dokumentasi:</h4>
                    <div class="rounded-xl overflow-hidden border border-slate-200 max-w-md bg-slate-900">
                        <img src="{{ asset('storage/' . $violation->proof_image) }}" alt="Bukti Pelanggaran" class="w-full h-auto object-contain max-h-80">
                    </div>
                </div>
            @endif

            <!-- Reporter & Status -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-4 border-t border-slate-100">
                <div>
                    <span class="text-slate-400 font-medium">Guru / Staf Pelapor:</span>
                    <p class="font-bold text-slate-800 text-sm mt-0.5">{{ $violation->reporter?->name ?? 'Staf Sekolah' }}</p>
                </div>

                <div>
                    <span class="text-slate-400 font-medium">Status Pembinaan Saat Ini:</span>
                    <p class="mt-0.5">
                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                            @if($violation->status == 'selesai') bg-emerald-100 text-emerald-800
                            @elseif($violation->status == 'dalam_pembinaan') bg-blue-100 text-blue-800
                            @else bg-amber-100 text-amber-800
                            @endif">
                            {{ str_replace('_', ' ', $violation->status) }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Handling Details (If already followed up) -->
            @if($violation->follow_up_action)
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs">
                    <p class="font-bold text-emerald-900">Tindak Lanjut & Hasil Pembinaan:</p>
                    <p class="text-emerald-800 mt-1 leading-relaxed">{{ $violation->follow_up_action }}</p>
                    <p class="text-emerald-600 text-[10px] mt-2 font-medium">
                        Ditangani oleh: {{ $violation->handledBy?->name ?? '-' }} ({{ $violation->handled_at?->translatedFormat('d M Y H:i') }})
                    </p>
                </div>
            @endif

        </div>

        <!-- Follow-up Update Form (for BK or Wali Kelas) -->
        <div class="p-6 bg-slate-50 border-t border-slate-200">
            <h4 class="font-bold text-slate-800 text-sm mb-1 flex items-center gap-2">
                <i data-lucide="edit-3" class="w-4 h-4 text-emerald-600"></i>
                <span>Perbarui Tindak Lanjut Pembinaan</span>
            </h4>
            <p class="text-xs text-slate-500 mb-4">Wali Kelas atau Guru BK dapat mencatat proses mediasi, konseling, dan status penyelesaian kasus.</p>

            <form action="{{ route('violations.update_status', $violation->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pilih Status Kasus</label>
                        <select name="status" id="status" required
                                class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="menunggu_tindakan" {{ $violation->status == 'menunggu_tindakan' ? 'selected' : '' }}>Menunggu Tindakan</option>
                            <option value="dalam_pembinaan" {{ $violation->status == 'dalam_pembinaan' ? 'selected' : '' }}>Dalam Pembinaan BK/Wali</option>
                            <option value="selesai" {{ $violation->status == 'selesai' ? 'selected' : '' }}>Selesai / Dituntaskan</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="follow_up_action" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Rincian Tindakan / Sanksi yang Telah Dijalankan</label>
                        <input type="text" name="follow_up_action" id="follow_up_action" required value="{{ old('follow_up_action', $violation->follow_up_action) }}"
                               placeholder="Contoh: Siswa telah membuat surat pernyataan dan dibina oleh guru BK..."
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    @if($violation->student?->net_points >= 30)
                        <a href="{{ route('summons.create', ['student_id' => $violation->student_id]) }}"
                           class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-600 hover:text-rose-700">
                            <i data-lucide="mail-warning" class="w-4 h-4"></i>
                            <span>Poin tinggi ({{ $violation->student?->net_points }} poin) - Buat Surat Panggilan Ortu &rarr;</span>
                        </a>
                    @else
                        <span></span>
                    @endif

                    <button type="submit" class="px-5 py-2 bg-navy-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                        <span>Simpan Pembaruan</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection
