@extends('layouts.app')
@section('title', 'Riwayat Presensi ASN - ATP')
@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex flex-col sm:flex-row justify-between items-center gap-3">
        <div>
            <h1 class="text-base font-bold text-gray-900">Riwayat Presensi</h1>
            <p class="text-xs text-gray-500">Log kehadiran harian, waktu presensi, dan keterlambatan.</p>
        </div>
        <form method="GET" action="{{ route('absensi.riwayat') }}" class="flex items-center space-x-2 text-xs">
            <select name="bulan" class="border rounded p-1.5 bg-white">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                        {{ Carbon\Carbon::create(null, $m, 1)->locale('id')->isoFormat('MMMM') }}
                    </option>
                @endfor
            </select>
            <input type="number" name="tahun" value="{{ $tahun }}" class="border rounded p-1.5 w-20 bg-white">
            <button type="submit" class="bg-[#1877F2] text-white px-3 py-1.5 rounded font-semibold">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold uppercase">
                <tr>
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">Pegawai</th>
                    <th class="p-3">Jam Masuk</th>
                    <th class="p-3">Jam Pulang</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Keterlambatan</th>
                    <th class="p-3">Jarak Radius</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($riwayats as $row)
                    <tr class="hover:bg-blue-50/40">
                        <td class="p-3 font-semibold">{{ \Carbon\Carbon::parse($row->tanggal)->locale('id')->isoFormat('dddd, D MMM Y') }}</td>
                        <td class="p-3">{{ $row->pegawai->nama ?? '-' }}</td>
                        <td class="p-3 font-mono">{{ $row->jam_masuk ? \Carbon\Carbon::parse($row->jam_masuk)->format('H:i:s') : '-' }}</td>
                        <td class="p-3 font-mono">{{ $row->jam_pulang ? \Carbon\Carbon::parse($row->jam_pulang)->format('H:i:s') : '-' }}</td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $row->status_kehadiran === 'HADIR' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $row->status_kehadiran }}
                            </span>
                        </td>
                        <td class="p-3 text-red-600 font-semibold">{{ $row->menit_keterlambatan ? $row->menit_keterlambatan . ' mnt' : 'Tepat Waktu' }}</td>
                        <td class="p-3 font-mono text-gray-500">{{ $row->jarak_masuk_meter ?? 0 }}m</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-400">Belum ada riwayat presensi di periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($riwayats->hasPages())
            <div class="p-3 border-t bg-gray-50">
                {{ $riwayats->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
