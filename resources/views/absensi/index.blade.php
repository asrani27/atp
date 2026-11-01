@extends('layouts.app')
@section('title', 'Presensi Online ASN - ATP')
@section('content')
<div class="max-w-3xl mx-auto space-y-4">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex justify-between items-center">
        <div>
            <h1 class="text-base font-bold text-gray-900">Presensi GPS Mandiri</h1>
            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
        </div>
        <div class="bg-blue-50 px-3 py-1.5 rounded text-right">
            <span class="text-[10px] text-blue-600 font-bold block">Jadwal</span>
            <span class="text-xs font-mono font-bold">{{ $jadwalHariIni ? $jadwalHariIni->jam_masuk . ' - ' . $jadwalHariIni->jam_pulang : 'Libur' }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- MASUK -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <span class="px-2 py-0.5 rounded text-xs font-bold bg-blue-100 text-[#1877F2]">PRESENSI MASUK</span>
            @if($presensiHariIni && $presensiHariIni->jam_masuk)
                <div class="bg-gray-50 p-3 rounded text-xs mt-3 space-y-1">
                    <p>Waktu: <b>{{ \Carbon\Carbon::parse($presensiHariIni->jam_masuk)->format('H:i:s') }}</b></p>
                    <p>Status: <b class="text-emerald-600">{{ $presensiHariIni->status_kehadiran }}</b></p>
                    <p>Jarak: {{ $presensiHariIni->jarak_masuk_meter }}m</p>
                </div>
            @else
                <form action="{{ route('absensi.checkin') }}" method="POST" class="mt-3 space-y-2">
                    @csrf
                    <input type="hidden" name="latitude" id="lat_m" value="-3.441544">
                    <input type="hidden" name="longitude" id="long_m" value="114.843232">
                    <select name="lokasi_id" required class="w-full text-xs border rounded p-2 bg-white">
                        @foreach($lokasis as $lok)
                            <option value="{{ $lok->id }}">{{ $lok->nama }} ({{ $lok->radius_meter }}m)</option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-gray-500">GPS: <span id="gps_text">-3.441544, 114.843232</span></p>
                    <button type="submit" class="w-full bg-[#1877F2] hover:bg-[#166FE5] text-white font-bold py-2 rounded text-xs">
                        Absen Masuk
                    </button>
                </form>
            @endif
        </div>

        <!-- PULANG -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <span class="px-2 py-0.5 rounded text-xs font-bold bg-amber-100 text-amber-800">PRESENSI PULANG</span>
            @if($presensiHariIni && $presensiHariIni->jam_pulang)
                <div class="bg-gray-50 p-3 rounded text-xs mt-3 space-y-1">
                    <p>Waktu: <b>{{ \Carbon\Carbon::parse($presensiHariIni->jam_pulang)->format('H:i:s') }}</b></p>
                    <p>Jarak: {{ $presensiHariIni->jarak_pulang_meter }}m</p>
                </div>
            @else
                <form action="{{ route('absensi.checkout') }}" method="POST" class="mt-3 space-y-2">
                    @csrf
                    <input type="hidden" name="latitude" id="lat_p" value="-3.441544">
                    <input type="hidden" name="longitude" id="long_p" value="114.843232">
                    <select name="lokasi_id" required class="w-full text-xs border rounded p-2 bg-white">
                        @foreach($lokasis as $lok)
                            <option value="{{ $lok->id }}">{{ $lok->nama }} ({{ $lok->radius_meter }}m)</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 rounded text-xs">
                        Absen Pulang
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="text-center pt-2">
        <a href="{{ route('absensi.riwayat') }}" class="text-xs font-semibold text-[#1877F2]">Riwayat Presensi &rarr;</a>
    </div>
</div>

<script>
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(pos) {
        document.getElementById('lat_m').value = pos.coords.latitude;
        document.getElementById('long_m').value = pos.coords.longitude;
        document.getElementById('lat_p').value = pos.coords.latitude;
        document.getElementById('long_p').value = pos.coords.longitude;
        document.getElementById('gps_text').innerText = pos.coords.latitude.toFixed(6) + ', ' + pos.coords.longitude.toFixed(6);
    });
}
</script>
@endsection
