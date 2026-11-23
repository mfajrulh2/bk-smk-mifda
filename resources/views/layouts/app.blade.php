<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Presensi & Disiplin Siswa') - {{ config('app.name', 'SIPRESMEN') }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        navy: {
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
        }
    </style>
</head>
<body class="h-full flex text-slate-800 antialiased" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         x-cloak 
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden">
    </div>

    <!-- Sidebar Navigation -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-72 bg-navy-900 text-slate-200 flex flex-col transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 border-r border-slate-800">
        
        <!-- Logo & Brand Header -->
        <div class="h-16 flex items-center justify-between px-6 bg-navy-950 border-b border-slate-800/80">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-400 flex items-center justify-center text-white shadow-lg shadow-emerald-900/40">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="font-bold text-white text-base leading-tight tracking-wide">E-DISIPLIN</h1>
                    <p class="text-xs text-emerald-400 font-medium">Presensi & Poin Pelanggaran</p>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <!-- User Role Badge -->
        @auth
        <div class="px-6 py-4 bg-navy-950/40 border-b border-slate-800/50 flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center font-bold text-emerald-400 uppercase text-sm border border-slate-600">
                {{ substr(Auth::user()->name, 0, 2) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider
                        @if(Auth::user()->isAdmin()) bg-amber-500/20 text-amber-300 border border-amber-500/30
                        @elseif(Auth::user()->isBK()) bg-purple-500/20 text-purple-300 border border-purple-500/30
                        @elseif(Auth::user()->isGuru()) bg-blue-500/20 text-blue-300 border border-blue-500/30
                        @else bg-emerald-500/20 text-emerald-300 border border-emerald-500/30
                        @endif">
                        {{ Auth::user()->role }}
                    </span>
                </div>
            </div>
        </div>
        @endauth

        <!-- Navigation Menu Links -->
        <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1.5 text-sm font-medium">
            @auth
                @if(Auth::user()->role !== 'siswa_ortu')
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-emerald-600 text-white font-semibold shadow-md shadow-emerald-900/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        <span>Dashboard Utama</span>
                    </a>

                    <!-- Section: Presensi Siswa -->
                    <div class="pt-4 pb-1 px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Presensi Siswa</div>
                    
                    <a href="{{ route('attendance.input') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-colors {{ request()->routeIs('attendance.input') ? 'bg-emerald-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i data-lucide="user-check" class="w-5 h-5"></i>
                        <span>Input Rekap Bulanan</span>
                    </a>

                    <a href="{{ route('attendance.recap') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-colors {{ request()->routeIs('attendance.recap') ? 'bg-emerald-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i data-lucide="calendar-days" class="w-5 h-5"></i>
                        <span>Rekapitulasi Kehadiran</span>
                    </a>

                    <!-- Section: Kedisiplinan & Poin -->
                    <div class="pt-4 pb-1 px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Tata Tertib & Poin</div>

                    <a href="{{ route('violations.create') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-colors {{ request()->routeIs('violations.create') ? 'bg-emerald-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-400"></i>
                        <span>Catat Pelanggaran</span>
                    </a>

                    <a href="{{ route('violations.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-colors {{ request()->routeIs('violations.index') || request()->routeIs('violations.show') ? 'bg-emerald-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                        <span>Data Pelanggaran</span>
                    </a>

                    <a href="{{ route('summons.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-colors {{ request()->routeIs('summons.*') ? 'bg-emerald-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i data-lucide="mail-warning" class="w-5 h-5 text-rose-400"></i>
                        <span>Surat Panggilan Ortu (SP)</span>
                    </a>

                    <a href="{{ route('restorations.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-colors {{ request()->routeIs('restorations.*') ? 'bg-emerald-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i data-lucide="sparkles" class="w-5 h-5 text-teal-400"></i>
                        <span>Pemutihan / Konseling</span>
                    </a>

                    <!-- Section: Master Data (Admin Only) -->
                    @if(Auth::user()->isAdmin())
                        <div class="pt-4 pb-1 px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Master Data</div>

                        <a href="{{ route('master.students') }}" 
                           class="flex items-center gap-3 px-3.5 py-2 rounded-lg transition-colors {{ request()->routeIs('master.students*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                            <span>Data Siswa & Impor</span>
                        </a>

                        <a href="{{ route('master.classes') }}" 
                           class="flex items-center gap-3 px-3.5 py-2 rounded-lg transition-colors {{ request()->routeIs('master.classes*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i data-lucide="door-open" class="w-4 h-4"></i>
                            <span>Data Kelas & Wali</span>
                        </a>

                        <a href="{{ route('master.violation_types') }}" 
                           class="flex items-center gap-3 px-3.5 py-2 rounded-lg transition-colors {{ request()->routeIs('master.violation_types*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i data-lucide="sliders" class="w-4 h-4"></i>
                            <span>Jenis & Poin Pelanggaran</span>
                        </a>

                        <a href="{{ route('master.thresholds') }}" 
                           class="flex items-center gap-3 px-3.5 py-2 rounded-lg transition-colors {{ request()->routeIs('master.thresholds*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i data-lucide="shield-alert" class="w-4 h-4"></i>
                            <span>Aturan Sanksi & SP</span>
                        </a>

                        <a href="{{ route('master.users') }}" 
                           class="flex items-center gap-3 px-3.5 py-2 rounded-lg transition-colors {{ request()->routeIs('master.users*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i data-lucide="users" class="w-4 h-4"></i>
                            <span>Pengguna / Guru</span>
                        </a>

                        <a href="{{ route('master.settings') }}" 
                           class="flex items-center gap-3 px-3.5 py-2 rounded-lg transition-colors {{ request()->routeIs('master.settings*') ? 'bg-slate-800 text-white font-medium' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <i data-lucide="settings" class="w-4 h-4"></i>
                            <span>Pengaturan Sekolah</span>
                        </a>
                    @endif

                @else
                    <!-- Portal Siswa Menu -->
                    <a href="{{ route('portal.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg bg-emerald-600 text-white font-semibold">
                        <i data-lucide="user" class="w-5 h-5"></i>
                        <span>Portal Saya</span>
                    </a>
                @endif
            @endauth

            <!-- Quick Public Portal Link -->
            <div class="pt-4 pb-1 px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Tautan Cepat</div>
            <a href="{{ route('portal.search') }}" target="_blank"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white transition-colors">
                <i data-lucide="search" class="w-5 h-5 text-sky-400"></i>
                <span>Cek Poin Siswa (NISN)</span>
            </a>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-800/80 bg-navy-950/60">
            @auth
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-rose-300 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 rounded-lg transition-colors">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span>Keluar Sistem</span>
                </button>
            </form>
            @endauth
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 flex items-center justify-between shadow-xs sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div class="hidden sm:block">
                    <h2 class="text-base font-bold text-slate-800">@yield('page_title', 'Sistem Informasi Sekolah')</h2>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('portal.search') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg bg-sky-50 text-sky-700 hover:bg-sky-100 border border-sky-200 transition-colors">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                    <span>Portal Ortu</span>
                </a>

                @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-emerald-600 text-white font-bold text-xs flex items-center justify-center">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="text-xs font-medium text-slate-700 hidden md:inline">{{ Auth::user()->name }}</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
                    </button>

                    <div x-show="open" @click.outside="open = false" x-cloak
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-50">
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="text-xs text-slate-500">Masuk sebagai</p>
                            <p class="text-xs font-semibold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                        </div>
                        <a href="{{ route('profile') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-slate-700 hover:bg-slate-50">
                            <i data-lucide="user-cog" class="w-4 h-4"></i>
                            <span>Profil Akun</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-xs text-rose-600 hover:bg-rose-50">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
                @endauth
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-4 sm:px-6 pt-4">
            @if(session('success'))
                <div class="p-4 mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-xs">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0"></i>
                    <div class="flex-1 text-sm font-medium">
                        {{ session('success') }}

                        @if(session('wa_link'))
                            <div class="mt-2.5">
                                <a href="{{ session('wa_link') }}" target="_blank"
                                   class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-xs transition-colors">
                                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                                    <span>Kirim Notifikasi via WhatsApp Sekarang</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 mb-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start gap-3 shadow-xs">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600 mt-0.5 shrink-0"></i>
                    <div class="flex-1 text-sm font-medium">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 mb-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-xs">
                    <div class="flex items-center gap-2 font-semibold text-sm mb-1">
                        <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i>
                        <span>Mohon periksa kembali isian formulir:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Page Content View -->
        <main class="flex-1 overflow-y-auto px-4 sm:px-6 pb-12">
            @yield('content')
        </main>
    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
    @stack('scripts')
</body>
</html>
