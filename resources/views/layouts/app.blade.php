<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ATP - Portal ASN')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #F0F2F5; color: #050505; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col">
    <!-- FB STYLE TOP NAVBAR -->
    <header class="bg-[#1877F2] text-white shadow sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                <a href="{{ route('portal.dashboard') }}" class="flex items-center space-x-2">
                    <div class="w-9 h-9 rounded-full bg-white text-[#1877F2] flex items-center justify-center font-black text-xl shadow">A</div>
                    <span class="font-bold text-lg tracking-tight">ATP ASN</span>
                </a>

                <nav class="flex space-x-1 sm:space-x-2">
                    <a href="{{ route('portal.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-semibold flex items-center space-x-1.5 transition {{ request()->routeIs('portal.dashboard') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }}">
                        <i class="fa-solid fa-house"></i>
                        <span class="hidden md:inline">Portal</span>
                    </a>
                    <a href="{{ route('absensi.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold flex items-center space-x-1.5 transition {{ request()->is('absensi*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }}">
                        <i class="fa-solid fa-fingerprint"></i>
                        <span class="hidden md:inline">Presensi</span>
                    </a>
                    <a href="{{ route('jam-kerja.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold flex items-center space-x-1.5 transition {{ request()->is('jam-kerja*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }}">
                        <i class="fa-solid fa-clock"></i>
                        <span class="hidden md:inline">Jam Kerja</span>
                    </a>
                    <a href="{{ route('tpp.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold flex items-center space-x-1.5 transition {{ request()->is('tpp*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }}">
                        <i class="fa-solid fa-award"></i>
                        <span class="hidden md:inline">TPP</span>
                    </a>
                    <a href="{{ route('pajak.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold flex items-center space-x-1.5 transition {{ request()->is('pajak*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10' }}">
                        <i class="fa-solid fa-calculator"></i>
                        <span class="hidden md:inline">Pajak TER</span>
                    </a>
                    @if(Auth::check() && Auth::user()->hasAnyRole(['superadmin', 'admin_skpd']))
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-semibold flex items-center space-x-1.5 transition text-amber-200 hover:bg-white/10">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span class="hidden md:inline">Panel Admin</span>
                        </a>
                    @endif
                </nav>

                <div class="flex items-center space-x-3">
                    @auth
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-[#1877F2] flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="hidden lg:block text-left text-xs leading-tight">
                                <p class="font-bold truncate max-w-[140px]">{{ Auth::user()->name }}</p>
                                <span class="text-blue-200 text-[10px]">{{ Auth::user()->roles->pluck('name')->first() ?? 'User' }}</span>
                            </div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-blue-100 hover:text-white hover:bg-white/10 rounded-full transition" title="Logout">
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- ALERTS -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-lg shadow-sm flex items-center space-x-2 mb-4">
                <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-lg shadow-sm flex items-center space-x-2 mb-4">
                <i class="fa-solid fa-triangle-exclamation text-red-600 text-lg"></i>
                <div class="text-sm font-medium">{{ session('error') }}</div>
            </div>
        @endif
    </div>

    <!-- MAIN BODY -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 w-full">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-gray-200 py-3 text-center text-xs text-gray-500 mt-auto">
        <p class="font-medium text-gray-600">ATP ASN &copy; {{ date('Y') }} — Absensi, TPP & Pajak ASN Terpadu.</p>
    </footer>

    @stack('scripts')
</body>
</html>
