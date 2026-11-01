@extends('layouts.admin')
@section('title', 'Data Pegawai & SKPD - Admin ATP')
@section('page_title', 'Master Pegawai')
@section('title_header', 'Data Pegawai ASN & Penempatan')

@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-xl shadow-xs border border-gray-200 p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
            <h2 class="text-base font-bold text-gray-900">Daftar Pegawai ASN</h2>
            <p class="text-xs text-gray-500">Kelola master profil kepegawaian, SKPD, unit kerja, jabatan, dan kategori PTKP/TER.</p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="bg-blue-50 text-[#1877F2] font-semibold text-xs px-3 py-1.5 rounded-lg border border-blue-200">
                <i class="fa-solid fa-users mr-1"></i>{{ $pegawais->total() }} Total ASN
            </span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-mono min-w-[750px]">
                <thead class="bg-gray-50 text-gray-500 text-[10px] uppercase font-sans border-b">
                    <tr>
                        <th class="p-3">Nama Pegawai & NIP</th>
                        <th class="p-3">SKPD & Unit Kerja</th>
                        <th class="p-3">Jabatan</th>
                        <th class="p-3 text-center">Status PTKP</th>
                        <th class="p-3 text-right">Gaji Pokok</th>
                        <th class="p-3 text-right">Pagu TPP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pegawais as $p)
                        <tr class="hover:bg-blue-50/20">
                            <td class="p-3 font-sans">
                                <div class="font-bold text-gray-900">{{ $p->user->name ?? $p->nip }}</div>
                                <div class="text-[10px] text-gray-500 font-mono">{{ $p->nip }}</div>
                            </td>
                            <td class="p-3 font-sans text-gray-700">
                                <div class="font-semibold">{{ $p->skpd->nama_skpd ?? '-' }}</div>
                                <div class="text-[10px] text-gray-500">{{ $p->unitKerja->nama_unit ?? '-' }}</div>
                            </td>
                            <td class="p-3 font-sans text-gray-700">
                                {{ $p->jabatan->nama_jabatan ?? '-' }}
                            </td>
                            <td class="p-3 text-center font-sans">
                                <span class="bg-blue-100 text-[#1877F2] font-bold px-2 py-0.5 rounded text-[11px]">
                                    {{ $p->status_ptkp ?? 'TK/0' }}
                                </span>
                            </td>
                            <td class="p-3 text-right font-mono text-gray-800">
                                Rp {{ number_format($p->gaji_pokok, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-right font-mono font-bold text-emerald-600">
                                Rp {{ number_format($p->tpp_basic, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-400 font-sans">Belum ada data pegawai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pegawais->hasPages())
            <div class="p-3 border-t border-gray-100">
                {{ $pegawais->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
