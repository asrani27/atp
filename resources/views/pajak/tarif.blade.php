@extends('layouts.app')
@section('title', 'Tabel Tarif TER PP 58/2023 - ATP')
@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex flex-col sm:flex-row justify-between items-center gap-3">
        <div>
            <h1 class="text-base font-bold text-gray-900">Tabel Referensi Tarif Efektif Rata-Rata (TER)</h1>
            <p class="text-xs text-gray-500">Peraturan Pemerintah No. 58 Tahun 2023 tentang Pemotongan PPh Pasal 21.</p>
        </div>
        <div class="flex space-x-1 text-xs">
            <a href="{{ route('pajak.tarif', ['kategori' => 'TER_A']) }}" class="px-3 py-1.5 rounded font-bold transition {{ $kategori === 'TER_A' ? 'bg-[#1877F2] text-white' : 'bg-gray-100 text-gray-700' }}">TER A</a>
            <a href="{{ route('pajak.tarif', ['kategori' => 'TER_B']) }}" class="px-3 py-1.5 rounded font-bold transition {{ $kategori === 'TER_B' ? 'bg-[#1877F2] text-white' : 'bg-gray-100 text-gray-700' }}">TER B</a>
            <a href="{{ route('pajak.tarif', ['kategori' => 'TER_C']) }}" class="px-3 py-1.5 rounded font-bold transition {{ $kategori === 'TER_C' ? 'bg-[#1877F2] text-white' : 'bg-gray-100 text-gray-700' }}">TER C</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left text-xs font-mono">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold uppercase text-[10px] font-sans">
                <tr>
                    <th class="p-3">Kategori</th>
                    <th class="p-3">Penghasilan Bruto Minimum</th>
                    <th class="p-3">Penghasilan Bruto Maksimum</th>
                    <th class="p-3 text-right">Tarif Efektif (%)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($tarifs as $t)
                    <tr class="hover:bg-blue-50/40">
                        <td class="p-3 font-sans font-bold text-[#1877F2]">{{ $t->kategori }}</td>
                        <td class="p-3">Rp {{ number_format($t->penghasilan_bruto_min, 0, ',', '.') }}</td>
                        <td class="p-3">{{ $t->penghasilan_bruto_max ? 'Rp ' . number_format($t->penghasilan_bruto_max, 0, ',', '.') : 'Diatasnya' }}</td>
                        <td class="p-3 text-right font-bold text-red-600 bg-red-50/20">{{ $t->tarif_persen }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
