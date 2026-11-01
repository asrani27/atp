<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke ATP - Portal ASN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #F0F2F5; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-4xl w-full grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        <!-- Left: Brand Hero -->
        <div class="text-center md:text-left">
            <h1 class="text-[#1877F2] font-black text-5xl md:text-6xl tracking-tight mb-2">atp</h1>
            <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-4">Satu Portal Terpadu ASN</h2>
            <p class="text-gray-600 text-base leading-relaxed">
                Kelola <strong>Absensi Online</strong> berbasis geofencing, <strong>TPP & Aktivitas Kinerja</strong>, serta perhitungan <strong>Pajak TER PP 58/2023</strong> dalam satu sistem terintegrasi.
            </p>
        </div>

        <!-- Right: Login Card -->
        <div class="bg-white p-6 sm:p-8 rounded-lg shadow-md border border-gray-200">
            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-3 py-2 rounded text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <input type="text" name="username" id="username_input" required
                           value="{{ old('username') }}"
                           placeholder="NIP / Username"
                           class="w-full px-4 py-3 rounded-md border border-gray-300 text-base focus:outline-none focus:border-[#1877F2] focus:ring-1 focus:ring-[#1877F2]">
                </div>

                <div>
                    <input type="password" name="password" id="password_input" required
                           placeholder="Kata Sandi"
                           class="w-full px-4 py-3 rounded-md border border-gray-300 text-base focus:outline-none focus:border-[#1877F2] focus:ring-1 focus:ring-[#1877F2]">
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-[#1877F2] focus:ring-0 mr-2">
                        Ingat Saya
                    </label>
                </div>

                <button type="submit" 
                        class="w-full bg-[#1877F2] hover:bg-[#166FE5] text-white font-bold py-3 px-4 rounded-md text-lg transition duration-150">
                    Masuk
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                <p class="text-xs text-gray-500 mb-2 font-semibold uppercase tracking-wider">Akses Uji Coba Cepat:</p>
                <div class="flex flex-wrap gap-2 justify-center">
                    <button type="button" onclick="fillLogin('admin', 'password123')" 
                            class="text-xs bg-blue-50 text-[#1877F2] hover:bg-blue-100 font-semibold px-2.5 py-1.5 rounded transition">
                        Superadmin
                    </button>
                    <button type="button" onclick="fillLogin('adminskpd', 'password123')" 
                            class="text-xs bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-semibold px-2.5 py-1.5 rounded transition">
                        Admin SKPD
                    </button>
                    <button type="button" onclick="fillLogin('198501152010011012', 'password123')" 
                            class="text-xs bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-semibold px-2.5 py-1.5 rounded transition">
                        ASN Fauzi
                    </button>
                    <button type="button" onclick="fillLogin('199008202015032005', 'password123')" 
                            class="text-xs bg-amber-50 text-amber-700 hover:bg-amber-100 font-semibold px-2.5 py-1.5 rounded transition">
                        ASN Siti
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fillLogin(user, pass) {
            document.getElementById('username_input').value = user;
            document.getElementById('password_input').value = pass;
        }
    </script>
</body>
</html>
