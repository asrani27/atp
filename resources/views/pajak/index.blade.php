@extends('layouts.app')
@section('title', 'Pajak ASN & BPJS - ATP')
@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Perpajakan & Iuran Jaminan</span>
            <h1 class="text-xl font-bold text-gray-900 mt-1">Pajak TER PP 58/2023 & BPJS ASN</h1>
            <p class="text-xs text-gray-500 mt-0.5">Sistem perhitungan Tarif Efektif Rata-Rata (TER) PPh 21 dan Iuran BPJS Kesehatan 1% & 4%.</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('pajak.simulator') }}" class="bg-[#1877F2] hover:bg-[#166FE5] text-white font-bold px-3.5 py-2 rounded text-xs transition">
                <i class="fa-solid fa-calculator mr-1.5"></i> Simulator TER
            </a>
            <a href="{{ route('pajak.tarif') }}" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-3.5 py-2 rounded text-xs transition">
                <i class="fa-solid fa-table mr-1.5"></i> Tabel Tarif PP 58/2023
            </a>
        </div>
    </div>

    <!-- ASN PTKP & TAX CATEGORY TABLE -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-x-auto">
        <div class="p-4 border-b border-gray-200 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-bold text-xs text-gray-800 uppercase">Daftar Status Pajak & PTKP Pegawai</h3>
            <span class="text-xs text-gray-500">Total: {{ $pegawais->count() }} Pegawai</span>
        </div>
        <table class="w-full text-left text-xs whitespace-nowrap">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold uppercase text-[10px]">
                <tr>
                    <th class="p-3">Nama Pegawai</th>
                    <th class="p-3">NIP & NPWP</th>
                    <th class="p-3">Status PTKP</th>
                    <th class="p-3">Kategori TER</th>
                    <th class="p-3 text-right">Gaji Pokok</th>
                    <th class="p-3 text-right">Pagu TPP</th>
                    <th class="p-3 text-right">Total Bruto</th>
                    <th class="p-3 text-right">Tarif TER</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 font-mono">
                @foreach($pegawais as $p)
                    @php
                        $kat = in_array($p->status_ptkp, ['TK/2','TK/3','K/1','K/2']) ? 'TER_B' : ($p->status_ptkp === 'K/3' ? 'TER_C' : 'TER_A');
                        $pagu = $p->jabatan->pagu_total ?? 4000000;
                        $totalBruto = $p->gaji_pokok + $pagu;
                        $tarifPersen = \App\Models\TarifPajakTer::getTarif($kat, $totalBruto);
                    @endphp
                    <tr class="hover:bg-blue-50/40">
                        <td class="p-3 font-sans font-bold text-gray-900">{{ $p->nama_lengkap }}</td>
                        <td class="p-3 font-mono text-gray-500">
                            <div>{{ $p->nip }}</div>
                            <div class="text-[10px] text-gray-400">NPWP: {{ $p->npwp ?? '-' }}</div>
                        </td>
                        <td class="p-3 font-sans">
                            <span class="bg-blue-100 text-[#1877F2] font-bold px-2 py-0.5 rounded text-[11px]">{{ $p->status_ptkp }}</span>
                        </td>
                        <td class="p-3 font-sans">
                            <span class="bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded text-[11px]">{{ str_replace('_', ' ', $kat) }}</span>
                        </td>
                        <td class="p-3 text-right">Rp {{ number_format($p->gaji_pokok, 0, ',', '.') }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($pagu, 0, ',', '.') }}</td>
                        <td class="p-3 text-right font-bold text-gray-900">Rp {{ number_format($totalBruto, 0, ',', '.') }}</td>
                        <td class="p-3 text-right font-bold text-red-600 bg-red-50/40">{{ $tarifPersen }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
