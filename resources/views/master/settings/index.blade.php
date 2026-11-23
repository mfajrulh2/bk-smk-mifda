@extends('layouts.app')

@section('title', 'Pengaturan Sekolah & Notifikasi')
@section('page_title', 'Pengaturan Instansi & Notifikasi')

@section('content')
<div class="py-6 max-w-4xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <div class="p-6 border-b border-slate-100 bg-slate-50/70">
            <h3 class="font-extrabold text-slate-800 text-base flex items-center gap-2">
                <i data-lucide="settings" class="w-5 h-5 text-emerald-600"></i>
                <span>Profil Sekolah & Konfigurasi WhatsApp Gateway</span>
            </h3>
            <p class="text-xs text-slate-500 mt-1">
                Data ini akan otomatis dicantumkan pada kop surat resmi, rekapitulasi presensi, dan notifikasi ke orang tua.
            </p>
        </div>

        <form action="{{ route('master.settings.update') }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- Bagian 1: Identitas Sekolah -->
            <div class="space-y-4">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider pb-1 border-b border-slate-100">
                    1. Identitas Lembaga Pendidikan
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="school_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Resmi Sekolah <span class="text-rose-500">*</span></label>
                        <input type="text" name="school_name" id="school_name" required value="{{ old('school_name', $settings->school_name) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="school_code" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">NPSN / Kode Sekolah</label>
                        <input type="text" name="school_code" id="school_code" value="{{ old('school_code', $settings->school_code) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label for="school_address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Lengkap</label>
                    <input type="text" name="school_address" id="school_address" value="{{ old('school_address', $settings->school_address) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="school_phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Telepon Sekolah</label>
                        <input type="text" name="school_phone" id="school_phone" value="{{ old('school_phone', $settings->school_phone) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="school_email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Resmi</label>
                        <input type="email" name="school_email" id="school_email" value="{{ old('school_email', $settings->school_email) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Bagian 2: Pejabat Penandatangan -->
            <div class="space-y-4 pt-4">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider pb-1 border-b border-slate-100">
                    2. Pejabat Penandatangan Dokumen & Surat
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="principal_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Kepala Sekolah & Gelar</label>
                        <input type="text" name="principal_name" id="principal_name" value="{{ old('principal_name', $settings->principal_name) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="principal_nip" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">NIP Kepala Sekolah</label>
                        <input type="text" name="principal_nip" id="principal_nip" value="{{ old('principal_nip', $settings->principal_nip) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="counseling_head_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Koordinator Bimbingan Konseling (BK)</label>
                        <input type="text" name="counseling_head_name" id="counseling_head_name" value="{{ old('counseling_head_name', $settings->counseling_head_name) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="counseling_head_nip" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">NIP Koordinator BK</label>
                        <input type="text" name="counseling_head_nip" id="counseling_head_nip" value="{{ old('counseling_head_nip', $settings->counseling_head_nip) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Bagian 3: Tahun Pelajaran Aktif -->
            <div class="space-y-4 pt-4">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider pb-1 border-b border-slate-100">
                    3. Periode Akademik Aktif
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="active_academic_year" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tahun Ajaran Aktif</label>
                        <input type="text" name="active_academic_year" id="active_academic_year" required value="{{ old('active_academic_year', $settings->active_academic_year) }}"
                               placeholder="2026/2027"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="active_semester" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Semester Aktif</label>
                        <select name="active_semester" id="active_semester" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="Ganjil" {{ $settings->active_semester == 'Ganjil' ? 'selected' : '' }}>Semester Ganjil (1)</option>
                            <option value="Genap" {{ $settings->active_semester == 'Genap' ? 'selected' : '' }}>Semester Genap (2)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Bagian 4: WhatsApp Gateway Token -->
            <div class="space-y-4 pt-4">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider pb-1 border-b border-slate-100 flex items-center gap-2">
                    <i data-lucide="message-circle" class="w-4 h-4 text-emerald-600"></i>
                    <span>4. Integrasi WhatsApp Gateway (Opsional)</span>
                </h4>

                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-600 leading-relaxed space-y-2">
                    <p>
                        Aplikasi secara bawaan sudah menyediakan <strong>Tombol Direct Link WhatsApp Web / Mobile</strong> yang siap pakai tanpa perlu biaya gateway.
                    </p>
                    <p>
                        Jika sekolah memiliki token API gateway (seperti <em>Fonnte / Wablas</em>) untuk pengiriman pesan otomatis di latar belakang, masukkan token di bawah ini:
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="wa_gateway_token" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Token API WhatsApp Gateway</label>
                        <input type="text" name="wa_gateway_token" id="wa_gateway_token" value="{{ old('wa_gateway_token', $settings->wa_gateway_token) }}"
                               placeholder="Contoh: vQ123... (Fonnte API Token)"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="wa_gateway_endpoint" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">URL Endpoint API</label>
                        <input type="text" name="wa_gateway_endpoint" id="wa_gateway_endpoint" value="{{ old('wa_gateway_endpoint', $settings->wa_gateway_endpoint) }}"
                               placeholder="https://api.fonnte.com/send"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-emerald-700/30 transition-all flex items-center gap-2">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Simpan Pengaturan</span>
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
