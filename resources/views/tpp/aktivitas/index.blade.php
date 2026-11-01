@extends('layouts.app')
@section('title', 'Log Aktivitas Harian - ATP TPP')
@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex flex-col sm:flex-row justify-between items-center gap-3">
        <div>
            <h1 class="text-base font-bold text-gray-900">Log Aktivitas Harian Kinerja</h1>
            <p class="text-xs text-gray-500">Daftar laporan kerja harian dan status verifikasi pejabat penilai.</p>
        </div>
        <a href="{{ route('tpp.index') }}" class="text-xs text-[#1877F2] font-semibold">&larr; Kembali ke Dashboard TPP</a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold uppercase text-[10px]">
                <tr>
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">Pegawai</th>
                    <th class="p-3">Waktu Pelaksanaan</th>
                    <th class="p-3">Durasi</th>
                    <th class="p-3">Uraian Kinerja</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($aktivitasList as $item)
                    <tr class="hover:bg-blue-50/40">
                        <td class="p-3 font-semibold whitespace-nowrap">{{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->isoFormat('D MMM Y') }}</td>
                        <td class="p-3 whitespace-nowrap">{{ $item->pegawai->nama ?? '-' }}</td>
                        <td class="p-3 font-mono whitespace-nowrap">{{ substr($item->jam_mulai,0,5) }} - {{ substr($item->jam_selesai,0,5) }}</td>
                        <td class="p-3 font-bold text-emerald-600">{{ $item->durasi_menit }} mnt</td>
                        <td class="p-3 max-w-xs text-gray-800">{{ $item->uraian }}</td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold 
                                {{ $item->status_validasi === 'DISETUJUI' ? 'bg-emerald-100 text-emerald-800' : ($item->status_validasi === 'DITOLAK' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                {{ $item->status_validasi }}
                            </span>
                        </td>
                        <td class="p-3 text-right whitespace-nowrap">
                            @if($item->status_validasi === 'PENDING' && Auth::user()->hasRole(['superadmin', 'validator', 'admin_skpd']))
                                <form action="{{ route('tpp.aktivitas.validate', $item->id) }}" method="POST" class="inline-flex space-x-1">
                                    @csrf
                                    <input type="hidden" name="status" value="DISETUJUI">
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-2 py-1 rounded text-[10px]">
                                        Setujui
                                    </button>
                                </form>
                                <form action="{{ route('tpp.aktivitas.validate', $item->id) }}" method="POST" class="inline-flex space-x-1">
                                    @csrf
                                    <input type="hidden" name="status" value="DITOLAK">
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-2 py-1 rounded text-[10px]">
                                        Tolak
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400 text-[10px]">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-400">Belum ada aktivitas yang dicatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($aktivitasList->hasPages())
            <div class="p-3 border-t bg-gray-50">
                {{ $aktivitasList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
