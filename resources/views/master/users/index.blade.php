@extends('layouts.app')

@section('title', 'Manajemen Pengguna & Guru')
@section('page_title', 'Master Pengguna & Dewan Guru')

@section('content')
<div class="py-6 space-y-6" x-data="{ createModal: false, editModal: false, editData: {} }">

    <!-- Top Action Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="font-extrabold text-slate-800 text-base">Akun Pengguna Sistem</h3>
            <p class="text-xs text-slate-500">Kelola hak akses untuk Administrator TU, Guru BK, Wali Kelas, dan Dewan Guru</p>
        </div>

        <button type="button" @click="createModal = true"
                class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-700/30 transition-all flex items-center gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>+ Tambah Akun Baru</span>
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Nama Lengkap</th>
                        <th class="py-3 px-4">Username / NIP</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4 text-center">Peran (Role)</th>
                        <th class="py-3 px-4">No. Telepon / WA</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $idx => $u)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 text-center text-xs font-semibold text-slate-400">
                                {{ $users->firstItem() + $idx }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-800 text-xs sm:text-sm">
                                {{ $u->name }}
                            </td>
                            <td class="py-3.5 px-4 text-xs font-mono text-slate-600">
                                <div class="font-bold">{{ $u->username }}</div>
                                <div class="text-[10px] text-slate-400">{{ $u->identifier_number ?: '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-600">
                                {{ $u->email }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider
                                    @if($u->isAdmin()) bg-amber-100 text-amber-800 border border-amber-200
                                    @elseif($u->isBK()) bg-purple-100 text-purple-800 border border-purple-200
                                    @elseif($u->isGuru()) bg-blue-100 text-blue-800 border border-blue-200
                                    @else bg-emerald-100 text-emerald-800 border border-emerald-200
                                    @endif">
                                    {{ $u->role }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-600">
                                {{ $u->phone ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <button type="button" @click="editData = { id: {{ $u->id }}, name: '{{ addslashes($u->name) }}', email: '{{ $u->email }}', role: '{{ $u->role }}', phone: '{{ $u->phone }}', identifier_number: '{{ $u->identifier_number }}' }; editModal = true;"
                                        class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 text-sm">Belum ada data akun pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Create User -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="createModal = false" class="bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-md w-full p-6 space-y-4">
            <h3 class="font-extrabold text-slate-800 text-base">Tambah Akun Pengguna Baru</h3>

            <form action="{{ route('master.users.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap & Gelar</label>
                    <input type="text" name="name" required placeholder="Contoh: Ahmad Syukron, S.Pd." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Username Login</label>
                        <input type="text" name="username" required placeholder="syukron" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Peran (Role)</label>
                        <select name="role" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                            <option value="guru">Guru / Wali Kelas</option>
                            <option value="bk">Guru BK / Kesiswaan</option>
                            <option value="admin">Administrator TU</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email</label>
                        <input type="email" name="email" required placeholder="guru@sekolah.sch.id" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kata Sandi</label>
                        <input type="password" name="password" required minlength="6" placeholder="••••••••" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">NIP / No Identitas</label>
                        <input type="text" name="identifier_number" placeholder="19800..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">No. WhatsApp</label>
                        <input type="text" name="phone" placeholder="081234567890" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="createModal = false" class="px-4 py-2 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.outside="editModal = false" class="bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-md w-full p-6 space-y-4">
            <h3 class="font-extrabold text-slate-800 text-base">Ubah Data Pengguna</h3>

            <form :action="'{{ url('master/users') }}/' + editData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email</label>
                        <input type="email" name="email" x-model="editData.email" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Peran (Role)</label>
                        <select name="role" x-model="editData.role" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                            <option value="guru">Guru / Wali Kelas</option>
                            <option value="bk">Guru BK / Kesiswaan</option>
                            <option value="admin">Administrator TU</option>
                            <option value="siswa_ortu">Portal Siswa/Ortu</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">NIP</label>
                        <input type="text" name="identifier_number" x-model="editData.identifier_number" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">No. WhatsApp</label>
                        <input type="text" name="phone" x-model="editData.phone" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Ganti Kata Sandi (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" minlength="6" placeholder="Password baru..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800">
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
