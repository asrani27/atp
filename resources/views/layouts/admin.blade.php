<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - ATP ASN')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #F0F2F5; color: #050505; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
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

    <!-- ADMIN SIDEBAR (Responsive Drawer on Mobile, Fixed Sidebar on Desktop) -->
    <aside id="admin-sidebar" 
           class="fixed inset-y-0 left-0 z-50 w-72 lg:w-64 bg-white flex flex-col shadow-2xl lg:shadow-none border-r border-[#E4E6EB] transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        
        <!-- SIDEBAR BRAND HEADER -->
        <div class="h-16 px-4 flex items-center justify-between border-b border-[#E4E6EB] bg-white">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5">
                <div class="w-9 h-9 rounded-full bg-[#1877F2] text-white flex items-center justify-center font-black text-xl shadow">A</div>
                <div>
                    <span class="font-black text-base tracking-tight text-[#1877F2] block leading-none">ATP ADMIN</span>
                    <span class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">Panel Pengelola</span>
                </div>
            </a>
            <button type="button" onclick="closeAdminSidebar()" class="lg:hidden p-2 rounded-lg text-gray-500 hover:text-gray-800 hover:bg-gray-100 focus:outline-none" aria-label="Tutup menu sidebar">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- USER CARD IN SIDEBAR -->
        <div class="p-3 mx-3 mt-3 bg-blue-50/70 rounded-xl border border-blue-100 flex items-center space-x-3">
            <div class="w-9 h-9 rounded-full bg-[#1877F2] text-white flex items-center justify-center font-bold text-xs shadow-sm flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="font-bold text-xs text-gray-900 truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                <div class="flex items-center space-x-1.5 mt-0.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="text-[11px] font-semibold text-[#1877F2] uppercase truncate">
                        {{ Auth::user()->roles->pluck('name')->first() ?? 'SUPERADMIN' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- SIDEBAR NAVIGATION MENU -->
        <nav class="flex-1 overflow-y-auto sidebar-scroll px-3 py-4 space-y-5">
            <div>
                <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Menu Utama</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-[#1877F2]' }}">
                        <i class="fa-solid fa-gauge-high w-4 text-center"></i>
                        <span>Dashboard Admin</span>
                    </a>
                    <a href="{{ route('portal.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold text-gray-700 hover:bg-blue-50 hover:text-[#1877F2] transition">
                        <i class="fa-solid fa-arrow-left-long w-4 text-center text-blue-500"></i>
                        <span>Kembali ke Portal ASN</span>
                    </a>
                </div>
            </div>

            <div>
                <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Presensi & Jadwal</p>
                <div class="space-y-1">
                    <a href="{{ route('jam-kerja.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->is('*jam-kerja*') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-[#1877F2]' }}">
                        <i class="fa-solid fa-clock w-4 text-center"></i>
                        <span>Jadwal & Jam Kerja</span>
                    </a>
                    <a href="{{ route('admin.lokasi.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.lokasi.*') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-[#1877F2]' }}">
                        <i class="fa-solid fa-map-location-dot w-4 text-center"></i>
                        <span>Lokasi & Geofence</span>
                    </a>
                    <a href="{{ route('absensi.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-100 hover:text-[#1877F2] transition">
                        <i class="fa-solid fa-fingerprint w-4 text-center"></i>
                        <span>Presensi Mandiri</span>
                    </a>
                    <a href="{{ route('absensi.riwayat') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-100 hover:text-[#1877F2] transition">
                        <i class="fa-solid fa-clipboard-list w-4 text-center"></i>
                        <span>Rekap Riwayat Presensi</span>
                    </a>
                </div>
            </div>

            <div>
                <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Kinerja & TPP</p>
                <div class="space-y-1">
                    <a href="{{ route('tpp.aktivitas') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->is('*aktivitas*') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-[#1877F2]' }}">
                        <i class="fa-solid fa-check-double w-4 text-center"></i>
                        <span>Validasi Aktivitas ASN</span>
                    </a>
                    <a href="{{ route('tpp.kalkulasi') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->is('*kalkulasi*') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-[#1877F2]' }}">
                        <i class="fa-solid fa-calculator w-4 text-center"></i>
                        <span>Kalkulasi Payroll TPP</span>
                    </a>
                    <a href="{{ route('tpp.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-100 hover:text-[#1877F2] transition">
                        <i class="fa-solid fa-award w-4 text-center"></i>
                        <span>Dashboard Kinerja TPP</span>
                    </a>
                </div>
            </div>

            <div>
                <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Pajak TER & Master</p>
                <div class="space-y-1">
                    <a href="{{ route('pajak.tarif') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->is('*tarif*') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-[#1877F2]' }}">
                        <i class="fa-solid fa-table-list w-4 text-center"></i>
                        <span>Tabel Tarif TER PP 58</span>
                    </a>
                    <a href="{{ route('admin.pegawai.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.pegawai.*') ? 'bg-[#1877F2] text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100 hover:text-[#1877F2]' }}">
                        <i class="fa-solid fa-users w-4 text-center"></i>
                        <span>Data Pegawai & SKPD</span>
                    </a>
                </div>
            </div>
        </nav>

        <!-- SIDEBAR FOOTER (LOGOUT) -->
        <div class="p-3 border-t border-[#E4E6EB] bg-gray-50/70">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 px-3 py-2 rounded-lg text-xs font-bold text-red-600 hover:bg-red-50 hover:text-red-700 border border-red-200 transition">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar dari Admin</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="lg:pl-64 flex flex-col flex-1 min-h-screen">
        
        <!-- ADMIN TOP HEADER (No Navbar Menus - Only Mobile Toggle, Breadcrumb & User Info) -->
        <header class="sticky top-0 z-30 bg-white border-b border-[#E4E6EB] shadow-xs">
            <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                
                <div class="flex items-center space-x-3">
                    <button type="button" onclick="openAdminSidebar()" class="lg:hidden p-2 rounded-lg text-gray-600 hover:text-[#1877F2] hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-[#1877F2]" aria-label="Buka menu navigasi sidebar">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <div class="flex items-center space-x-1.5 text-[11px] text-gray-500 font-medium">
                            <span class="text-[#1877F2] font-bold"><i class="fa-solid fa-shield-halved mr-1"></i>Panel Admin</span>
                            <span>/</span>
                            <span class="text-gray-700">@yield('page_title', 'Administrasi')</span>
                        </div>
                        <h1 class="text-base sm:text-lg font-bold text-gray-900 leading-tight">
                            @yield('title_header', 'ATP Administration')
                        </h1>
                    </div>
                </div>

                <div class="flex items-center space-x-2 sm:space-x-3">
                    <a href="{{ route('portal.dashboard') }}" class="hidden sm:inline-flex items-center space-x-1.5 bg-gray-100 hover:bg-blue-50 text-gray-700 hover:text-[#1877F2] text-xs font-semibold px-3 py-1.5 rounded-lg border border-gray-200 transition">
                        <i class="fa-solid fa-globe"></i>
                        <span>Portal ASN</span>
                    </a>
                    
                    <div class="h-6 w-px bg-gray-200 hidden sm:block"></div>

                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-[#1877F2] flex items-center justify-center font-bold text-xs border border-blue-200">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="hidden md:inline-block text-xs font-bold text-gray-700 max-w-[120px] truncate">
                            {{ Auth::user()->name ?? 'Admin' }}
                        </span>
                    </div>
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

        <!-- ADMIN FOOTER -->
        <footer class="bg-white border-t border-[#E4E6EB] py-3.5 px-4 sm:px-6 lg:px-8 text-center text-xs text-gray-500 mt-auto">
            <p class="font-medium text-gray-600">ATP ASN &copy; {{ date('Y') }} — Panel Administrasi Absensi, TPP & Pajak ASN.</p>
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

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') closeAdminSidebar();
        });
    </script>

    @stack('scripts')
</body>
</html>
