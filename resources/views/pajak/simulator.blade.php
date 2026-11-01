@extends('layouts.app')
@section('title', 'Simulator Pajak TER PP 58/2023 - ATP')
@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex justify-between items-center">
        <div>
            <h1 class="text-base font-bold text-gray-900">Kalkulator Simulasi Pajak TER PP 58/2023</h1>
            <p class="text-xs text-gray-500">Hitung pemotongan PPh 21 bulanan dan iuran BPJS Kesehatan 1% & 4%.</p>
        </div>
        <a href="{{ route('pajak.index') }}" class="text-xs text-[#1877F2] font-semibold">&larr; Kembali</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- FORM -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <h2 class="text-xs font-bold uppercase text-gray-700 mb-3">Parameter Penghasilan</h2>
            <form action="{{ route('pajak.simulator') }}" method="GET" class="space-y-3 text-xs">
                <div>
                    <label class="block font-semibold mb-1">Gaji Pokok ASN (Rp)</label>
                    <input type="number" name="gaji" required value="{{ $result['gaji'] }}" class="w-full border rounded p-2 font-mono">
                </div>
                <div>
                    <label class="block font-semibold mb-1">TPP (Rp)</label>
                    <input type="number" name="tpp" required value="{{ $result['tpp'] }}" class="w-full border rounded p-2 font-mono">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Status PTKP</label>
                    <select name="ptkp" required class="w-full border rounded p-2 bg-white">
                        <option value="TK/0" {{ $result['ptkp'] === 'TK/0' ? 'selected' : '' }}>TK/0 (TER A)</option>
                        <option value="TK/1" {{ $result['ptkp'] === 'TK/1' ? 'selected' : '' }}>TK/1 (TER A)</option>
                        <option value="K/0" {{ $result['ptkp'] === 'K/0' ? 'selected' : '' }}>K/0 (TER A)</option>
                        <option value="TK/2" {{ $result['ptkp'] === 'TK/2' ? 'selected' : '' }}>TK/2 (TER B)</option>
                        <option value="TK/3" {{ $result['ptkp'] === 'TK/3' ? 'selected' : '' }}>TK/3 (TER B)</option>
                        <option value="K/1" {{ $result['ptkp'] === 'K/1' ? 'selected' : '' }}>K/1 (TER B)</option>
                        <option value="K/2" {{ $result['ptkp'] === 'K/2' ? 'selected' : '' }}>K/2 (TER B)</option>
                        <option value="K/3" {{ $result['ptkp'] === 'K/3' ? 'selected' : '' }}>K/3 (TER C)</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-[#1877F2] hover:bg-[#166FE5] text-white font-bold py-2 rounded text-xs transition">
                    Hitung Simulasi
                </button>
            </form>
        </div>

        <!-- HASIL -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-bold uppercase text-gray-700">Hasil Kalkulasi</span>
                    <span class="bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded text-xs">
                        {{ str_replace('_', ' ', $result['kategori']) }}
                    </span>
                </div>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between py-1 border-b">
                        <span class="text-gray-600">Total Penghasilan Bruto:</span>
                        <span class="font-bold font-mono">Rp {{ number_format($result['total_bruto'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b">
                        <span class="text-gray-600">Tarif TER:</span>
                        <span class="font-bold text-[#1877F2]">{{ $result['tarif_persen'] }}%</span>
                    </div>
                    <div class="flex justify-between py-1 border-b">
                        <span class="text-gray-600">PPh 21 TER:</span>
                        <span class="font-bold font-mono text-red-600">- Rp {{ number_format($result['pph21'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b">
                        <span class="text-gray-600">BPJS 1% (Pegawai):</span>
                        <span class="font-bold font-mono text-red-600">- Rp {{ number_format($result['bpjs_pegawai'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b bg-gray-50 px-2 rounded">
                        <span class="text-gray-500">BPJS 4% (Pemda):</span>
                        <span class="font-mono text-gray-600">Rp {{ number_format($result['bpjs_pemda'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t bg-emerald-50 p-3 rounded">
                <span class="text-xs text-emerald-800 font-semibold block">Take Home Pay Bersih:</span>
                <span class="text-xl font-bold font-mono text-emerald-700 mt-0.5 block">
                    Rp {{ number_format($result['netto'], 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
