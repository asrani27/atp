<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ATP - Satu Portal Terpadu ASN')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { 
            background-color: #F0F2F5; 
            color: #050505; 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; 
        }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background-color: #CBD5E1; border-radius: 4px; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-[#F0F2F5] text-gray-900 antialiased flex flex-col">

    <!-- MOBILE SIDEBAR BACKDROP OVERLAY -->
    <div id="mobile-sidebar-backdrop" 
         class="fixed inset-0 bg-black/60 z-40 hidden transition-opacity duration-300 opacity-0 lg:hidden" 
         onclick="closeAdminSidebar()"
         aria-hidden="true"></div>

    <!-- RESPONSIVE SIDEBAR NAVIGATION (Drawer on Mobile, Fixed Sidebar on Desktop) -->
    <aside id="admin-sidebar" 
           class="fixed inset-y-0 left-0 z-50 w-72 lg:w-64 bg-white flex flex-col shadow-2xl lg:shadow-none border-r border-[#E4E6EB] transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        
        <!-- SIDEBAR BRAND HEADER -->
        <div class="h-16 px-4 flex items-center justify-between border-b border-[#E4E6EB] bg-white">
            <a href="{{ url('/') }}" class="flex items-center space-x-2.5">
                <div class="w-9 h-9 rounded-full bg-[#1877F2] text-white flex items-center justify-center font-black text-xl shadow">A</div>
                <div>
                    <span class="font-black text-base tracking-tight text-[#1877F2] block leading-none">ATP ASN</span>
                    <span class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">Portal Terpadu</span>
                </div>
            </a>
            <!-- Mobile Close Button -->
            <button type="button" 
                    onclick="closeAdminSidebar()" 
                    class="lg:hidden p-2 rounded-lg text-gray-500 hover:text-gray-800 hover:bg-gray-100 focus:outline-none" 
                    aria-label="Tutup menu sidebar">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- USER PROFILE CARD IN SIDEBAR -->
        @auth
        <div class="p-3 mx-3 mt-3 bg-blue-50/70 rounded-xl border border-blue-100 flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-[#1877F2] text-white flex items-center justify-center font-bold text-sm shadow-sm flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="font-bold text-xs text-gray-900 truncate">{{ Auth::user()->name }}</p>
                <div class="flex items-center space-x-1.5 mt-0.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="text-[10px] font-bold text-[#1877F2] uppercase truncate">
                        {{ Auth::user()->roles->pluck('name')->first() ?? 'PEGAWAI' }}
                    </span>
                </div>
            </div>
        </div>
        @endauth

        <!-- SIDEBAR NAVIGATION MENU ITEMS -->
        <nav class="flex-1 overflow-y-auto sidebar-scroll px-3 py-4 space-y-5">
            
            <!-- SECTION: PORTAL UTAMA & ADMIN -->
            <div>
                <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Portal Utama</p>
                <div class="space-y-1">
                    <a href="{{ url('/') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ (request()->is('/') || request()->routeIs('portal.dashboard')) ? 'bg-[#1877F2] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-[#1877F2]' }}">
                        <i class="fa-solid fa-house w-4 text-center"></i>
                        <span>Beranda Portal</span>
                    </a>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-[#1877F2]' }}">
                        <i class="fa-solid fa-gauge-high w-4 text-center text-amber-500"></i>
                        <span>Dashboard Admin</span>
                    </a>
                </div>
            </div>

            <!-- SECTION: MODUL PRESENSI GPS -->
            <div>
                <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Presensi & Jadwal</p>
                <div class="space-y-1">
                    <a href="{{ url('/absensi') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ (request()->is('absensi') && !request()->is('absensi/riwayat*')) ? 'bg-[#1877F2] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-[#1877F2]' }}">
                        <i class="fa-solid fa-fingerprint w-4 text-center"></i>
                        <span>Presensi Mandiri GPS</span>
                    </a>
                    <a href="{{ url('/absensi/riwayat') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->is('absensi/riwayat*') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-[#1877F2]' }}">
                        <i class="fa-solid fa-clipboard-list w-4 text-center"></i>
                        <span>Riwayat Kehadiran</span>
                    </a>
                    <a href="{{ route('jam-kerja.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->is('*jam-kerja*') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-[#1877F2]' }}">
                        <i class="fa-solid fa-clock w-4 text-center"></i>
                        <span>Jadwal & Jam Kerja</span>
                    </a>
                    <a href="{{ route('admin.lokasi.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->is('admin/lokasi*') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-[#1877F2]' }}">
                        <i class="fa-solid fa-map-location-dot w-4 text-center"></i>
                        <span>Lokasi & Geofence</span>
                    </a>
                </div>
            </div>

            <!-- SECTION: MODUL KINERJA & TPP -->
            <div>
                <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Kinerja & TPP</p>
                <div class="space-y-1">
                    <a href="{{ url('/tpp') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ (request()->is('tpp') && !request()->is('tpp/*')) ? 'bg-[#1877F2] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-[#1877F2]' }}">
                        <i class="fa-solid fa-award w-4 text-center"></i>
                        <span>Dashboard Kinerja TPP</span>
                    </a>
                    <a href="{{ url('/tpp/aktivitas') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->is('tpp/aktivitas*') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-[#1877F2]' }}">
                        <i class="fa-solid fa-check-double w-4 text-center"></i>
                        <span>Log Aktivitas Harian</span>
                    </a>
                    <a href="{{ url('/tpp/kalkulasi') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->is('tpp/kalkulasi*') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-[#1877F2]' }}">
                        <i class="fa-solid fa-calculator w-4 text-center"></i>
                        <span>Kalkulasi Realisasi TPP</span>
                    </a>
                </div>
            </div>

            <!-- SECTION: MODUL PAJAK TER PP 58/2023 -->
            <div>
                <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Pajak TER & BPJS</p>
                <div class="space-y-1">
                    <a href="{{ url('/pajak') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ (request()->is('pajak') && !request()->is('pajak/*')) ? 'bg-[#1877F2] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-[#1877F2]' }}">
                        <i class="fa-solid fa-file-invoice-dollar w-4 text-center"></i>
                        <span>Ringkasan Pajak ASN</span>
                    </a>
                    <a href="{{ url('/pajak/simulator') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->is('pajak/simulator*') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-[#1877F2]' }}">
                        <i class="fa-solid fa-coins w-4 text-center"></i>
                        <span>Simulator TER & BPJS</span>
                    </a>
                    <a href="{{ url('/pajak/tarif') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->is('pajak/tarif*') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-[#1877F2]' }}">
                        <i class="fa-solid fa-table-list w-4 text-center"></i>
                        <span>Tabel Tarif TER PP 58</span>
                    </a>
                </div>
            </div>

            <!-- SECTION: MASTER KEPEGAWAIAN -->
            <div>
                <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Data Master</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.pegawai.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->is('admin/pegawai*') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-[#1877F2]' }}">
                        <i class="fa-solid fa-users w-4 text-center"></i>
                        <span>Data Pegawai & SKPD</span>
                    </a>
                </div>
            </div>

        </nav>

        <!-- SIDEBAR FOOTER (LOGOUT) -->
        @auth
        <div class="p-3 border-t border-[#E4E6EB] bg-gray-50/70">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-center space-x-2 px-3 py-2 rounded-lg text-xs font-bold text-red-600 hover:bg-red-50 hover:text-red-700 border border-red-200 transition">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar dari Aplikasi</span>
                </button>
            </form>
        </div>
        @endauth

    </aside>

    <!-- MAIN CONTENT AREA (Offset on desktop by sidebar width, full-width on mobile) -->
    <div class="lg:pl-64 flex flex-col flex-1 min-h-screen">
        
        <!-- TOP HEADER (No Navbar Menus - Only Mobile Toggle, Page Breadcrumb & User Pill) -->
        <header class="sticky top-0 z-30 bg-white border-b border-[#E4E6EB] shadow-xs">
            <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                
                <!-- Left: Mobile Hamburger button & Breadcrumb Title -->
                <div class="flex items-center space-x-3">
                    <button type="button" 
                            onclick="openAdminSidebar()" 
                            class="lg:hidden p-2 rounded-lg text-gray-600 hover:text-[#1877F2] hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-[#1877F2]" 
                            aria-label="Buka menu navigasi sidebar">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <div class="flex items-center space-x-1.5 text-[11px] text-gray-500 font-medium">
                            <span class="text-[#1877F2] font-bold"><i class="fa-solid fa-circle-nodes mr-1"></i>ATP ASN</span>
                            <span>/</span>
                            <span class="text-gray-700">@yield('page_title', 'Modul ASN')</span>
                        </div>
                        <h1 class="text-base sm:text-lg font-bold text-gray-900 leading-tight truncate max-w-[200px] sm:max-w-md">
                            @yield('title_header', 'Aplikasi Absensi, TPP & Pajak ASN')
                        </h1>
                    </div>
                </div>

                <!-- Right: User Avatar & Quick Info -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    @auth
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-[#1877F2] flex items-center justify-center font-bold text-xs border border-blue-200">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="hidden sm:block text-left text-xs leading-tight">
                            <p class="font-bold text-gray-800 truncate max-w-[130px]">{{ Auth::user()->name }}</p>
                            <span class="text-gray-500 text-[10px]">{{ Auth::user()->roles->pluck('name')->first() ?? 'Pegawai' }}</span>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" 
                                title="Keluar">
                            <i class="fa-solid fa-right-from-bracket text-base"></i>
                        </button>
                    </form>
                    @endauth
                </div>

            </div>
        </header>

        <!-- FLASH ALERTS -->
        <div class="px-4 sm:px-6 lg:px-8 pt-4 w-full">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl shadow-sm flex items-center space-x-2.5 mb-4">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg flex-shrink-0"></i>
                    <div class="text-xs sm:text-sm font-medium">{{ session('success') }}</div>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-xl shadow-sm flex items-center space-x-2.5 mb-4">
                    <i class="fa-solid fa-triangle-exclamation text-red-600 text-lg flex-shrink-0"></i>
                    <div class="text-xs sm:text-sm font-medium">{{ session('error') }}</div>
                </div>
            @endif
        </div>

        <!-- MAIN BODY CONTENT -->
        <main class="flex-grow px-4 sm:px-6 lg:px-8 py-4 w-full">
            @yield('content')
        </main>

        <!-- FOOTER -->
        <footer class="bg-white border-t border-[#E4E6EB] py-3.5 px-4 sm:px-6 lg:px-8 text-center text-xs text-gray-500 mt-auto">
            <p class="font-medium text-gray-600">ATP ASN &copy; {{ date('Y') }} — Absensi, TPP & Pajak ASN Terpadu.</p>
        </footer>

    </div>

    <!-- RESPONSIVE SIDEBAR TOGGLE SCRIPT -->
    <script>
        function openAdminSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            if (sidebar && backdrop) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                setTimeout(() => { backdrop.classList.remove('opacity-0'); }, 10);
                document.body.classList.add('overflow-hidden', 'lg:overflow-auto');
            }
        }

        function closeAdminSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            if (sidebar && backdrop) {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('opacity-0');
                setTimeout(() => { backdrop.classList.add('hidden'); }, 300);
                document.body.classList.remove('overflow-hidden', 'lg:overflow-auto');
            }
        }

        window.openAppSidebar = openAdminSidebar;
        window.closeAppSidebar = closeAdminSidebar;

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') closeAdminSidebar();
        });
    </script>

    @stack('scripts')
</body>
</html>
