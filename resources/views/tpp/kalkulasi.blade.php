@extends('layouts.app')
@section('title', 'Kalkulasi TPP ASN - ATP')
@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex flex-col sm:flex-row justify-between items-center gap-3">
        <div>
            <h1 class="text-base font-bold text-gray-900">Rekapitulasi Kalkulasi TPP Bulanan</h1>
            <p class="text-xs text-gray-500">Kalkulasi 40% Disiplin Presensi + 60% Produktivitas Kinerja dikurangi BPJS & PPh 21 TER.</p>
        </div>
        <div class="flex items-center space-x-2 text-xs">
            <span class="font-bold text-gray-700">Periode: {{ Carbon\Carbon::create(null, $bulan, 1)->locale('id')->isoFormat('MMMM Y') }}</span>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-x-auto">
        <table class="w-full text-left text-xs whitespace-nowrap">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold uppercase text-[10px]">
                <tr>
                    <th class="p-3">Pegawai / NIP</th>
                    <th class="p-3">Jabatan & SKPD</th>
                    <th class="p-3 text-right">Pagu Total</th>
                    <th class="p-3 text-right">Disiplin (40%)</th>
                    <th class="p-3 text-right">Produktivitas (60%)</th>
                    <th class="p-3 text-right">BPJS 1%</th>
                    <th class="p-3 text-right">PPh 21 TER</th>
                    <th class="p-3 text-right font-bold text-emerald-700">TPP Bersih</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 font-mono">
                @foreach($pegawais as $p)
                    @php
                        $pagu = $p->jabatan->pagu_total ?? 4000000;
                        $disiplin = $pagu * 0.40;
                        $produktivitas = $pagu * 0.60;
                        $bruto = $disiplin + $produktivitas;
                        // BPJS 1% (dasar gaji pokok, max dasar 12jt)
                        $bpjs1 = round(min(12000000, $p->gaji_pokok) * 0.01);
                        // TER tarif
                        $kat = in_array($p->status_ptkp, ['TK/2','TK/3','K/1','K/2']) ? 'TER_B' : ($p->status_ptkp === 'K/3' ? 'TER_C' : 'TER_A');
                        $tarifPersen = \App\Models\TarifPajakTer::getTarif($kat, $p->gaji_pokok + $bruto);
                        $pph21 = round(($bruto * $tarifPersen) / 100);
                        $netto = $bruto - $bpjs1 - $pph21;
                    @endphp
                    <tr class="hover:bg-blue-50/40">
                        <td class="p-3 font-sans">
                            <span class="font-bold text-gray-900 block">{{ $p->nama_lengkap }}</span>
                            <span class="text-[10px] text-gray-400 font-mono">{{ $p->nip }}</span>
                        </td>
                        <td class="p-3 font-sans">
                            <span class="text-gray-800 block">{{ $p->jabatan->nama ?? '-' }}</span>
                            <span class="text-[10px] text-gray-500">{{ $p->skpd->singkatan ?? '-' }}</span>
                        </td>
                        <td class="p-3 text-right">Rp {{ number_format($pagu, 0, ',', '.') }}</td>
                        <td class="p-3 text-right text-blue-700">Rp {{ number_format($disiplin, 0, ',', '.') }}</td>
                        <td class="p-3 text-right text-indigo-700">Rp {{ number_format($produktivitas, 0, ',', '.') }}</td>
                        <td class="p-3 text-right text-red-600">-Rp {{ number_format($bpjs1, 0, ',', '.') }}</td>
                        <td class="p-3 text-right text-red-600">-Rp {{ number_format($pph21, 0, ',', '.') }} <span class="text-[10px] text-gray-400">({{ $tarifPersen }}%)</span></td>
                        <td class="p-3 text-right font-bold text-emerald-700 bg-emerald-50/50">
                            Rp {{ number_format($netto, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
