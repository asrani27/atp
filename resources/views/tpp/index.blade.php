@extends('layouts.app')
@section('title', 'Manajemen TPP & Kinerja - ATP')
@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Tambahan Penghasilan Pegawai</span>
            <h1 class="text-xl font-bold text-gray-900 mt-1">Dashboard Kinerja & Realisasi TPP</h1>
            <p class="text-xs text-gray-500 mt-0.5">Pantau capaian menit kerja efektif bulanan dan progres validasi aktivitas.</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('tpp.aktivitas') }}" class="bg-[#1877F2] hover:bg-[#166FE5] text-white font-bold px-3.5 py-2 rounded text-xs transition">
                <i class="fa-solid fa-list-check mr-1.5"></i> Aktivitas Harian
            </a>
            <a href="{{ route('tpp.kalkulasi') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-3.5 py-2 rounded text-xs transition">
                <i class="fa-solid fa-calculator mr-1.5"></i> Rekap Kalkulasi TPP
            </a>
        </div>
    </div>

    <!-- TARGET & PROGRESS STATS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <span class="text-xs font-semibold text-gray-500">Capaian Menit Kinerja</span>
            <div class="flex items-baseline space-x-2 mt-1">
                <span class="text-2xl font-bold text-emerald-600">{{ number_format($totalMenitAktivitas) }}</span>
                <span class="text-xs text-gray-400">/ {{ number_format($targetMenit) }} menit</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2 mt-3 overflow-hidden">
                <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" style="width: {{ $persenCapaian }}%"></div>
            </div>
            <p class="text-[11px] text-gray-400 mt-1.5 text-right">{{ $persenCapaian }}% dari target bulanan</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <span class="text-xs font-semibold text-gray-500">Status Validasi Atasan</span>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $aktivitasPending }} <span class="text-xs font-normal text-amber-600">Pending</span></p>
            <p class="text-xs text-gray-400 mt-3">Aktivitas yang belum disetujui pejabat penilai.</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <span class="text-xs font-semibold text-gray-500">Pagu TPP Jabatan Anda</span>
            <p class="text-2xl font-bold text-[#1877F2] mt-1">
                Rp {{ number_format(Auth::user()->pegawai->jabatan->pagu_total ?? 0, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-400 mt-3">40% Disiplin + 60% Produktivitas Kinerja</p>
        </div>
    </div>

    <!-- QUICK INPUT AKTIVITAS -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <h2 class="text-sm font-bold text-gray-800 mb-3 flex items-center">
            <i class="fa-solid fa-pen-to-square text-[#1877F2] mr-2"></i> Input Cepat Aktivitas Harian
        </h2>
        <form action="{{ route('tpp.aktivitas.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3 text-xs">
            @csrf
            <div>
                <label class="block font-semibold mb-1">Tanggal</label>
                <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block font-semibold mb-1">Jam Mulai</label>
                <input type="time" name="jam_mulai" required value="08:00" class="w-full border rounded p-2 font-mono">
            </div>
            <div>
                <label class="block font-semibold mb-1">Jam Selesai</label>
                <input type="time" name="jam_selesai" required value="10:00" class="w-full border rounded p-2 font-mono">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-[#1877F2] hover:bg-[#166FE5] text-white font-bold py-2 px-4 rounded transition">
                    + Simpan Aktivitas
                </button>
            </div>
            <div class="md:col-span-4">
                <label class="block font-semibold mb-1">Uraian Tugas / Hasil Output Kinerja</label>
                <input type="text" name="uraian" required placeholder="Contoh: Menyusun laporan rekapitulasi data presensi dan sinkronisasi server..." class="w-full border rounded p-2">
            </div>
        </form>
    </div>
</div>
@endsection
