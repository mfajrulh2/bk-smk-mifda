<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Kedisiplinan & Presensi Siswa - {{ $school->school_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-full py-12 px-4 flex flex-col items-center justify-center bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 text-slate-800">

    <div class="w-full max-w-2xl space-y-6">
        
        <!-- Header -->
        <div class="text-center text-white space-y-2">
            <div class="inline-flex w-14 h-14 rounded-2xl bg-emerald-500 items-center justify-center text-white shadow-xl shadow-emerald-500/30 mb-1">
                <i data-lucide="shield-check" class="w-8 h-8"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">PORTAL INFORMASI WALI SISWA</h1>
            <p class="text-xs sm:text-sm text-emerald-400 font-medium">{{ $school->school_name }}</p>
            <p class="text-xs text-slate-400 max-w-md mx-auto">
                Masukkan 10 digit Nomor Induk Siswa Nasional (NISN) untuk memantau rekapitulasi kehadiran dan poin tata tertib ananda secara transparan.
            </p>
        </div>

        <!-- Search Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 border border-slate-100">
            <form action="{{ route('portal.search') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </div>
                    <input type="text" name="nisn" required value="{{ request('nisn') }}"
                           placeholder="Ketik 10 digit NISN Siswa (Contoh: 0081234567)"
                           class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                </div>
                <button type="submit" class="py-3 px-6 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    <span>Cek Data</span>
                </button>
            </form>

            <div class="mt-4 flex items-center justify-between text-xs text-slate-400 pt-3 border-t border-slate-100">
                <span>Contoh NISN demo: <strong class="text-emerald-700 cursor-pointer" onclick="document.querySelector('input[name=nisn]').value='0081234567'">0081234567</strong></span>
                <a href="{{ route('login') }}" class="text-slate-600 hover:text-emerald-600 font-bold">
                    Login Pengurus &rarr;
                </a>
            </div>
        </div>

        <!-- Search Results (If Found) -->
        @if($searched)
            @if($student)
                <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden space-y-6 p-6 sm:p-8">
                    
                    <!-- Student Header -->
                    <div class="flex items-center justify-between gap-4 pb-6 border-b border-slate-100">
                        <div>
                            <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold uppercase">
                                Siswa Aktif
                            </span>
                            <h2 class="text-xl font-extrabold text-slate-900 mt-1">{{ $student->name }}</h2>
                            <p class="text-xs text-slate-500">
                                Kelas: <strong class="text-slate-800">{{ $student->class?->name }}</strong> &bull; 
                                NISN: <strong class="text-slate-800">{{ $student->nisn }}</strong>
                            </p>
                        </div>

                        <div class="text-right">
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Poin Aktif</p>
                            <p class="text-3xl font-black {{ $student->net_points >= 30 ? 'text-rose-600' : 'text-slate-800' }}">
                                {{ $student->net_points }} Poin
                            </p>
                        </div>
                    </div>

                    <!-- Attendance Stats -->
                    @php $att = $student->attendance_stats; @endphp
                    <div>
                        <div class="flex items-center justify-between mb-3 text-xs">
                            <span class="font-bold text-slate-700 uppercase tracking-wider">Kehadiran Semester Ini:</span>
                            <span class="font-black px-2.5 py-1 rounded-full {{ $att['percentage'] >= 85 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $att['percentage'] }}% Hadir
                            </span>
                        </div>
                        <div class="grid grid-cols-5 gap-2 text-center text-xs">
                            <div class="p-2.5 bg-emerald-50 rounded-xl">
                                <p class="font-black text-emerald-800 text-base">{{ $att['hadir'] }}</p>
                                <p class="text-emerald-700 text-[10px] font-semibold">Hadir</p>
                            </div>
                            <div class="p-2.5 bg-amber-50 rounded-xl">
                                <p class="font-black text-amber-800 text-base">{{ $att['terlambat'] }}</p>
                                <p class="text-amber-700 text-[10px] font-semibold">Telat</p>
                            </div>
                            <div class="p-2.5 bg-blue-50 rounded-xl">
                                <p class="font-black text-blue-800 text-base">{{ $att['sakit'] }}</p>
                                <p class="text-blue-700 text-[10px] font-semibold">Sakit</p>
                            </div>
                            <div class="p-2.5 bg-purple-50 rounded-xl">
                                <p class="font-black text-purple-800 text-base">{{ $att['izin'] }}</p>
                                <p class="text-purple-700 text-[10px] font-semibold">Izin</p>
                            </div>
                            <div class="p-2.5 bg-rose-50 rounded-xl">
                                <p class="font-black text-rose-800 text-base">{{ $att['alpa'] }}</p>
                                <p class="text-rose-700 text-[10px] font-semibold">Alpa</p>
                            </div>
                        </div>
                    </div>

                    <!-- Sanksi Aktif -->
                    @php $sanction = $student->current_sanction; @endphp
                    <div class="p-4 rounded-xl {{ $sanction ? 'bg-rose-50 border border-rose-200' : 'bg-emerald-50 border border-emerald-200' }} text-xs">
                        <p class="font-bold {{ $sanction ? 'text-rose-900' : 'text-emerald-900' }}">
                            Status Tata Tertib: {{ $sanction ? $sanction->action_name : 'Berkelakuan Baik & Tidak Ada Sanksi' }}
                        </p>
                        <p class="{{ $sanction ? 'text-rose-800' : 'text-emerald-800' }} mt-0.5 leading-relaxed">
                            {{ $sanction ? $sanction->description : 'Siswa mematuhi peraturan sekolah dengan disiplin. Teruskan pembinaan positif di rumah.' }}
                        </p>
                    </div>

                    <!-- Violation History -->
                    <div>
                        <h4 class="font-bold text-slate-700 text-xs uppercase tracking-wider mb-2">Riwayat Pelanggaran:</h4>
                        <div class="divide-y divide-slate-100 border border-slate-100 rounded-xl overflow-hidden">
                            @forelse($student->violations as $v)
                                <div class="p-3.5 text-xs flex items-center justify-between">
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $v->violationType?->name }}</p>
                                        <p class="text-slate-400 text-[11px]">{{ $v->violation_date->translatedFormat('d F Y') }}</p>
                                    </div>
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 font-black rounded-lg text-xs">
                                        +{{ $v->points }} Poin
                                    </span>
                                </div>
                            @empty
                                <div class="p-6 text-center text-slate-400 text-xs">
                                    Tidak ada catatan pelanggaran.
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            @else
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 text-center text-white border border-white/20">
                    <i data-lucide="user-x" class="w-10 h-10 text-rose-400 mx-auto mb-2"></i>
                    <h3 class="font-bold text-lg">Siswa Tidak Ditemukan</h3>
                    <p class="text-xs text-slate-300 mt-1">NISN "<strong>{{ request('nisn') }}</strong>" tidak terdaftar pada database aktif sekolah.</p>
                </div>
            @endif
        @endif

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
