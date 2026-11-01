@extends('layouts.app')
@section('title', 'Portal Beranda - ATP ASN')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <div class="lg:col-span-1 space-y-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center">
            <div class="w-14 h-14 mx-auto rounded-full bg-blue-100 text-[#1877F2] flex items-center justify-center font-bold text-xl mb-2">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <h2 class="font-bold text-gray-900 text-sm">{{ Auth::user()->name }}</h2>
            <p class="text-xs text-gray-500 font-mono">NIP: {{ Auth::user()->pegawai->nip ?? Auth::user()->username }}</p>
            <span class="mt-2 inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-50 text-[#1877F2]">
                {{ strtoupper(Auth::user()->roles->pluck('name')->first() ?? 'User') }}
            </span>
            @if(Auth::user()->pegawai)
                <div class="mt-3 pt-3 border-t border-gray-100 text-left text-xs space-y-1 text-gray-600">
                    <div><span class="text-gray-400 block text-[10px] font-bold">SKPD</span>{{ Auth::user()->pegawai->skpd->nama ?? '-' }}</div>
                    <div><span class="text-gray-400 block text-[10px] font-bold">Jabatan</span>{{ Auth::user()->pegawai->jabatan->nama ?? '-' }}</div>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 text-xs">
            <h3 class="font-bold text-gray-700 uppercase mb-2"><i class="fa-solid fa-globe text-[#1877F2] mr-1"></i> Multi-Domain</h3>
            <div class="space-y-1 font-mono text-[11px]">
                <div class="bg-gray-50 p-1.5 rounded flex justify-between"><span>absensi.local</span><span class="text-blue-700 font-bold">Presensi</span></div>
                <div class="bg-gray-50 p-1.5 rounded flex justify-between"><span>tpp.local</span><span class="text-emerald-700 font-bold">TPP</span></div>
                <div class="bg-gray-50 p-1.5 rounded flex justify-between"><span>pajakasn.local</span><span class="text-amber-700 font-bold">Pajak</span></div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-3 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('absensi.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:border-[#1877F2] transition block">
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-[#1877F2] flex items-center justify-center text-xl mb-2"><i class="fa-solid fa-fingerprint"></i></div>
                <h3 class="font-bold text-gray-900 text-sm">Presensi ASN</h3>
                <p class="text-xs text-gray-500 mt-1">Presensi GPS online & validasi radius kantor.</p>
                <span class="text-xs text-[#1877F2] font-semibold mt-2 block">Buka &rarr;</span>
            </a>
            <a href="{{ route('tpp.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:border-emerald-500 transition block">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-2"><i class="fa-solid fa-award"></i></div>
                <h3 class="font-bold text-gray-900 text-sm">Manajemen TPP</h3>
                <p class="text-xs text-gray-500 mt-1">Aktivitas harian & kalkulasi bulanan.</p>
                <span class="text-xs text-emerald-600 font-semibold mt-2 block">Buka &rarr;</span>
            </a>
            <a href="{{ route('pajak.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:border-amber-500 transition block">
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xl mb-2"><i class="fa-solid fa-calculator"></i></div>
                <h3 class="font-bold text-gray-900 text-sm">Pajak TER PP 58/2023</h3>
                <p class="text-xs text-gray-500 mt-1">PPh 21 TER bulanan & BPJS 1% & 4%.</p>
                <span class="text-xs text-amber-600 font-semibold mt-2 block">Buka &rarr;</span>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <h3 class="font-bold text-gray-800 text-xs uppercase mb-3"><i class="fa-solid fa-chart-line text-[#1877F2] mr-1"></i> Data Hari Ini</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-center">
                <div class="p-2.5 bg-gray-50 rounded"><p class="text-xs text-gray-500">Total ASN</p><p class="text-xl font-bold">{{ $stats['total_pegawai'] }}</p></div>
                <div class="p-2.5 bg-blue-50 rounded"><p class="text-xs text-blue-600">Presensi Hari Ini</p><p class="text-xl font-bold text-[#1877F2]">{{ $stats['presensi_today'] }}</p></div>
                <div class="p-2.5 bg-emerald-50 rounded"><p class="text-xs text-emerald-600">Tepat Waktu</p><p class="text-xl font-bold text-emerald-600">{{ $stats['hadir_today'] }}</p></div>
                <div class="p-2.5 bg-indigo-50 rounded"><p class="text-xs text-indigo-600">Pola Jam Kerja</p><p class="text-xl font-bold text-indigo-600">{{ $stats['pola_jam_kerja'] }}</p></div>
            </div>
        </div>

        <div class="bg-[#1877F2] text-white rounded-lg p-4 shadow-sm flex items-center justify-between">
            <div>
                <h4 class="font-bold text-sm"><i class="fa-solid fa-sliders mr-1.5"></i> Panel Jam Kerja Dinamis</h4>
                <p class="text-xs text-blue-100">Atur pola jam kerja 5 hari kantor, 6 hari sekolah/puskesmas, ramadhan, & shift.</p>
            </div>
            <a href="{{ route('jam-kerja.index') }}" class="bg-white text-[#1877F2] font-bold px-3 py-1.5 rounded text-xs">
                Kelola Jam Kerja &rarr;
            </a>
        </div>
    </div>
</div>
@endsection
