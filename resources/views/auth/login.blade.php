<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Sistem Presensi & Poin Pelanggaran Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full flex items-center justify-center p-4 bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950">

    <div class="w-full max-w-md">
        
        <!-- Brand Header -->
        <div class="text-center mb-6">
            <div class="inline-flex w-14 h-14 rounded-2xl bg-emerald-500 items-center justify-center text-white shadow-xl shadow-emerald-500/30 mb-3">
                <i data-lucide="shield-check" class="w-8 h-8"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">E-DISIPLIN SEKOLAH</h1>
            <p class="text-xs text-emerald-400 font-medium mt-1">Sistem Rekapitulasi Presensi & Poin Pelanggaran Siswa</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 border border-slate-100">
            <h2 class="text-lg font-bold text-slate-800 mb-1">Masuk ke Sistem</h2>
            <p class="text-xs text-slate-500 mb-6">Gunakan akun Pengurus Sekolah (Admin/Guru/BK) atau Akun Siswa/Wali Murid</p>

            @if(session('error'))
                <div class="mb-4 p-3.5 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-rose-600"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->has('login'))
                <div class="mb-4 p-3.5 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-rose-600"></i>
                    <span>{{ $errors->first('login') }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="login" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Username / NIP / NISN</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="login" id="login" required value="{{ old('login', 'admin') }}"
                               class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all text-slate-800"
                               placeholder="Contoh: admin atau 0081234567">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </div>
                        <input type="password" name="password" id="password" required value="admin123"
                               class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white transition-all text-slate-800"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                    <a href="{{ route('portal.search') }}" class="text-emerald-600 font-semibold hover:underline">
                        Cek NISN Siswa &rarr;
                    </a>
                </div>

                <button type="submit"
                        class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    <span>Masuk Aplikasi</span>
                </button>
            </form>

            <!-- Demo Account Hints -->
            <div class="mt-6 pt-5 border-t border-slate-100">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2.5 text-center">Akun Demo Pengujian:</p>
                <div class="grid grid-cols-2 gap-2 text-[11px]">
                    <button type="button" onclick="fillCred('admin', 'admin123')" class="p-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-left transition-colors">
                        <p class="font-bold text-slate-800">👑 Admin TU</p>
                        <p class="text-slate-500 text-[10px]">admin / admin123</p>
                    </button>
                    <button type="button" onclick="fillCred('guru_bk', 'bk123')" class="p-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-left transition-colors">
                        <p class="font-bold text-slate-800">🧠 Guru BK</p>
                        <p class="text-slate-500 text-[10px]">guru_bk / bk123</p>
                    </button>
                    <button type="button" onclick="fillCred('wali_kelas', 'guru123')" class="p-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-left transition-colors">
                        <p class="font-bold text-slate-800">📋 Wali Kelas</p>
                        <p class="text-slate-500 text-[10px]">wali_kelas / guru123</p>
                    </button>
                    <button type="button" onclick="fillCred('siswa1', 'siswa123')" class="p-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg text-left transition-colors">
                        <p class="font-bold text-slate-800">🎓 Portal Siswa</p>
                        <p class="text-slate-500 text-[10px]">siswa1 / siswa123</p>
                    </button>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-slate-400 mt-6">
            &copy; 2026 E-Disiplin Sekolah. Dikembangkan untuk pencatatan presensi & penegakan tata tertib.
        </p>
    </div>

    <script>
        lucide.createIcons();
        function fillCred(login, pass) {
            document.getElementById('login').value = login;
            document.getElementById('password').value = pass;
        }
    </script>
</body>
</html>
