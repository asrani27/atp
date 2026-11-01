@extends('layouts.admin')
@section('title', 'Dashboard Admin - ATP ASN')
@section('page_title', 'Ringkasan Eksekutif')
@section('title_header', 'Dashboard Pengelola ATP')

@section('content')
<div class="space-y-6">

    <!-- KPI STATS CARDS -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
        <div class="bg-white rounded-xl p-4 shadow-xs border border-gray-200 flex flex-col justify-between">
            <div class="flex items-center justify-between text-gray-500 mb-2">
                <span class="text-[11px] font-bold uppercase">Total Pegawai</span>
                <i class="fa-solid fa-users text-[#1877F2]"></i>
            </div>
            <div class="text-2xl font-black text-gray-900">{{ $stats['totalPegawai'] }}</div>
            <span class="text-[10px] text-gray-400 mt-1">ASN Terdaftar</span>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-xs border border-gray-200 flex flex-col justify-between">
            <div class="flex items-center justify-between text-gray-500 mb-2">
                <span class="text-[11px] font-bold uppercase">Total SKPD</span>
                <i class="fa-solid fa-building text-indigo-500"></i>
            </div>
            <div class="text-2xl font-black text-gray-900">{{ $stats['totalSkpd'] }}</div>
            <span class="text-[10px] text-gray-400 mt-1">Perangkat Daerah</span>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-xs border border-gray-200 flex flex-col justify-between">
            <div class="flex items-center justify-between text-gray-500 mb-2">
                <span class="text-[11px] font-bold uppercase">Pola Jam Kerja</span>
                <i class="fa-solid fa-clock text-amber-500"></i>
            </div>
            <div class="text-2xl font-black text-gray-900">{{ $stats['totalPola'] }}</div>
            <span class="text-[10px] text-gray-400 mt-1">Jadwal Dinamis</span>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-xs border border-gray-200 flex flex-col justify-between">
            <div class="flex items-center justify-between text-gray-500 mb-2">
                <span class="text-[11px] font-bold uppercase">Geofence Lokasi</span>
                <i class="fa-solid fa-map-location-dot text-emerald-500"></i>
            </div>
            <div class="text-2xl font-black text-gray-900">{{ $stats['totalLokasi'] }}</div>
            <span class="text-[10px] text-gray-400 mt-1">Titik Kantor Radius</span>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-xs border border-gray-200 flex flex-col justify-between">
            <div class="flex items-center justify-between text-gray-500 mb-2">
                <span class="text-[11px] font-bold uppercase">Presensi Hari Ini</span>
                <i class="fa-solid fa-fingerprint text-blue-500"></i>
            </div>
            <div class="text-2xl font-black text-gray-900">{{ $stats['presensiHariIni'] }}</div>
            <span class="text-[10px] text-gray-400 mt-1">Check-in Real-time</span>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-xs border border-gray-200 flex flex-col justify-between">
            <div class="flex items-center justify-between text-gray-500 mb-2">
                <span class="text-[11px] font-bold uppercase">Pending Validasi</span>
                <i class="fa-solid fa-hourglass-half text-amber-600"></i>
            </div>
            <div class="text-2xl font-black text-amber-600">{{ $stats['aktivitasPending'] }}</div>
            <span class="text-[10px] text-gray-400 mt-1">Aktivitas Kinerja</span>
        </div>
    </div>

    <!-- QUICK ACTIONS BANNER -->
    <div class="bg-gradient-to-r from-[#1877F2] to-blue-600 rounded-2xl p-5 text-white shadow-md flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h3 class="font-bold text-lg">Panel Kendali Cepat Administrator</h3>
            <p class="text-xs text-blue-100 mt-0.5">Kelola jadwal kerja fleksibel, pantau radius kantor GPS, dan validasi berkas kinerja ASN.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('jam-kerja.index') }}" class="bg-white text-[#1877F2] hover:bg-blue-50 font-bold px-3.5 py-2 rounded-lg text-xs shadow transition flex items-center space-x-1.5">
                <i class="fa-solid fa-clock"></i>
                <span>Kelola Jam Kerja</span>
            </a>
            <a href="{{ route('admin.lokasi.index') }}" class="bg-white/20 hover:bg-white/30 text-white font-bold px-3.5 py-2 rounded-lg text-xs border border-white/30 transition flex items-center space-x-1.5">
                <i class="fa-solid fa-map-location-dot"></i>
                <span>Radius Geofence</span>
            </a>
            <a href="{{ route('tpp.aktivitas') }}" class="bg-white/20 hover:bg-white/30 text-white font-bold px-3.5 py-2 rounded-lg text-xs border border-white/30 transition flex items-center space-x-1.5">
                <i class="fa-solid fa-check-double"></i>
                <span>Validasi Aktivitas</span>
            </a>
        </div>
    </div>

    <!-- TWO COLUMN LAYOUT: POLA JAM KERJA & LOG PRESENSI TERKINI -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- POLA JAM KERJA CARD -->
        <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50/50">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-clock text-[#1877F2]"></i>
                    <h3 class="font-bold text-sm text-gray-900">Jadwal Jam Kerja Terdaftar</h3>
                </div>
                <a href="{{ route('jam-kerja.index') }}" class="text-xs font-semibold text-[#1877F2] hover:underline">
                    Lihat Semua &rarr;
                </a>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($polas as $pola)
                    <div class="p-4 flex items-center justify-between hover:bg-blue-50/20 transition">
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-xs text-gray-900">{{ $pola->nama }}</span>
                                @if($pola->is_default)
                                    <span class="bg-emerald-100 text-emerald-800 text-[9px] font-bold px-1.5 py-0.5 rounded">DEFAULT</span>
                                @endif
                            </div>
                            <p class="text-[11px] text-gray-500 mt-0.5">{{ $pola->details->count() }} Hari dikonfigurasi &bull; Tipe: {{ $pola->tipe }}</p>
                        </div>
                        <a href="{{ route('jam-kerja.edit', $pola->id) }}" class="text-xs bg-gray-50 border border-gray-200 hover:border-[#1877F2] hover:text-[#1877F2] px-2.5 py-1 rounded-lg font-semibold transition">
                            Edit
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- RECENT ATTENDANCE FEED -->
        <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50/50">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-fingerprint text-emerald-600"></i>
                    <h3 class="font-bold text-sm text-gray-900">Log Presensi Terkini</h3>
                </div>
                <a href="{{ route('absensi.riwayat') }}" class="text-xs font-semibold text-[#1877F2] hover:underline">
                    Riwayat Lengkap &rarr;
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 text-gray-500 text-[10px] uppercase font-sans border-b">
                        <tr>
                            <th class="p-3">Pegawai</th>
                            <th class="p-3">Jam Masuk</th>
                            <th class="p-3">Jarak GPS</th>
                            <th class="p-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-mono">
                        @forelse($latestPresensi as $lp)
                            <tr class="hover:bg-blue-50/20">
                                <td class="p-3 font-sans">
                                    <p class="font-bold text-gray-900">{{ $lp->pegawai->user->name ?? 'Pegawai' }}</p>
                                    <span class="text-[10px] text-gray-500 font-mono">{{ $lp->pegawai->nip ?? '-' }}</span>
                                </td>
                                <td class="p-3">{{ $lp->jam_masuk ? \Carbon\Carbon::parse($lp->jam_masuk)->format('H:i') : '-' }}</td>
                                <td class="p-3 text-gray-600">{{ $lp->jarak_masuk_meter ? $lp->jarak_masuk_meter . 'm' : '-' }}</td>
                                <td class="p-3 font-sans">
                                    @if($lp->status_kehadiran === 'HADIR')
                                        <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full">HADIR</span>
                                    @elseif($lp->status_kehadiran === 'TERLAMBAT')
                                        <span class="bg-red-100 text-red-800 text-[10px] font-bold px-2 py-0.5 rounded-full">TERLAMBAT</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $lp->status_kehadiran }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-6 text-center text-gray-400 font-sans">Belum ada data presensi hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
