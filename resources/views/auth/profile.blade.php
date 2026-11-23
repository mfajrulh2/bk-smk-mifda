@extends('layouts.app')

@section('title', 'Profil Akun Saya')
@section('page_title', 'Profil & Pengaturan Akun')

@section('content')
<div class="py-6 max-w-2xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <div class="p-6 border-b border-slate-100 bg-slate-50/70">
            <h3 class="font-extrabold text-slate-800 text-base flex items-center gap-2">
                <i data-lucide="user-cog" class="w-5 h-5 text-emerald-600"></i>
                <span>Informasi Profil & Keamanan Akun</span>
            </h3>
            <p class="text-xs text-slate-500 mt-1">Perbarui nama, alamat surel, nomor kontak WhatsApp, dan kata sandi masuk.</p>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="p-6 sm:p-8 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" required value="{{ old('name', $user->name) }}"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="username" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Username</label>
                    <input type="text" id="username" disabled value="{{ $user->username }}"
                           class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm font-mono text-slate-500 cursor-not-allowed">
                    <p class="text-[10px] text-slate-400 mt-1">Username dikelola oleh Administrator.</p>
                </div>

                <div>
                    <label for="role" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Peran Sistem (Role)</label>
                    <input type="text" id="role" disabled value="{{ strtoupper($user->role) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm font-bold text-slate-500 cursor-not-allowed">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" id="email" required value="{{ old('email', $user->email) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">No. WhatsApp</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                           placeholder="081234567890"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <!-- Password Change Section -->
            <div class="pt-4 border-t border-slate-100 space-y-4">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ganti Kata Sandi (Opsional)</h4>

                <div>
                    <label for="current_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password" id="current_password"
                           placeholder="Masukkan kata sandi lama jika ingin mengganti"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="new_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi Baru</label>
                        <input type="password" name="new_password" id="new_password" minlength="6"
                               placeholder="Minimal 6 karakter"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                               placeholder="Ketik ulang kata sandi baru"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-emerald-700/30 transition-all flex items-center gap-2">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Simpan Perubahan Profil</span>
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
