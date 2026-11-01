@extends('layouts.admin')
@section('title', 'Lokasi & Radius Geofence - Admin ATP')
@section('page_title', 'Lokasi Presensi')
@section('title_header', 'Manajemen Lokasi & Radius Geofence')

@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-xl shadow-xs border border-gray-200 p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
            <h2 class="text-base font-bold text-gray-900">Titik Koordinat & Radius Kantor</h2>
            <p class="text-xs text-gray-500">Validasi server-side Haversine formula menghitung jarak real-time dari titik ini.</p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="bg-blue-50 text-[#1877F2] font-semibold text-xs px-3 py-1.5 rounded-lg border border-blue-200">
                <i class="fa-solid fa-satellite-dish mr-1"></i>{{ $lokasis->count() }} Titik Aktif
            </span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-mono min-w-[650px]">
                <thead class="bg-gray-50 text-gray-500 text-[10px] uppercase font-sans border-b">
                    <tr>
                        <th class="p-3">Nama Kantor / Lokasi</th>
                        <th class="p-3">SKPD Induk</th>
                        <th class="p-3">Latitude</th>
                        <th class="p-3">Longitude</th>
                        <th class="p-3 text-right">Radius Izin</th>
                        <th class="p-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($lokasis as $lokasi)
                        <tr class="hover:bg-blue-50/20">
                            <td class="p-3 font-sans font-bold text-gray-900">
                                <div class="flex items-center space-x-2">
                                    <div class="w-7 h-7 rounded-lg bg-blue-100 text-[#1877F2] flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        <i class="fa-solid fa-building"></i>
                                    </div>
                                    <div>
                                        <div>{{ $lokasi->nama_lokasi }}</div>
                                        <div class="text-[10px] text-gray-400 font-normal">{{ $lokasi->alamat ?? 'Alamat terdaftar' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3 font-sans text-gray-700">
                                {{ $lokasi->skpd->nama_skpd ?? 'Semua SKPD' }}
                            </td>
                            <td class="p-3 text-gray-600">{{ $lokasi->latitude }}</td>
                            <td class="p-3 text-gray-600">{{ $lokasi->longitude }}</td>
                            <td class="p-3 text-right font-bold text-[#1877F2]">
                                {{ $lokasi->radius_meter }} meter
                            </td>
                            <td class="p-3 text-center font-sans">
                                @if($lokasi->is_aktif)
                                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full">GEOFENCE AKTIF</span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded-full">NONAKTIF</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-400 font-sans">Tidak ada lokasi presensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
