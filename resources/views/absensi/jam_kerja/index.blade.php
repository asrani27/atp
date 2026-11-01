@extends('layouts.app')
@section('title', 'Admin Jam Kerja Dinamis - ATP')
@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex justify-between items-center">
        <div>
            <h1 class="text-base font-bold text-gray-900">Panel Pengaturan Jam Kerja Dinamis</h1>
            <p class="text-xs text-gray-500">Pola kerja 5 hari kantor, 6 hari sekolah/puskesmas, ramadhan, dan shift.</p>
        </div>
        <a href="{{ route('jam-kerja.create') }}" class="bg-[#1877F2] hover:bg-[#166FE5] text-white font-bold px-3 py-1.5 rounded text-xs">
            + Tambah Pola
        </a>
    </div>

    <div class="space-y-4">
        @foreach($polas as $pola)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-4 py-2.5 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <span class="font-bold text-sm text-gray-900">{{ $pola->nama }}</span>
                        @if($pola->is_default)
                            <span class="ml-2 bg-emerald-100 text-emerald-800 text-[10px] font-bold px-1.5 py-0.5 rounded">DEFAULT</span>
                        @endif
                        <span class="ml-1 bg-gray-200 text-gray-700 text-[10px] px-1.5 py-0.5 rounded">{{ $pola->tipe }}</span>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('jam-kerja.edit', $pola->id) }}" class="text-xs bg-white border px-2.5 py-1 rounded hover:text-[#1877F2]">Edit</a>
                        @if(!$pola->is_default)
                            <form action="{{ route('jam-kerja.destroy', $pola->id) }}" method="POST" onsubmit="return confirm('Hapus pola ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs bg-white border border-red-200 text-red-600 px-2 py-1 rounded">Hapus</button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-mono">
                        <thead class="bg-gray-50 text-gray-500 text-[10px] uppercase font-sans border-b">
                            <tr>
                                <th class="p-2">Hari</th>
                                <th class="p-2">Jam Kerja</th>
                                <th class="p-2">Absen Masuk</th>
                                <th class="p-2">Absen Pulang</th>
                                <th class="p-2">Toleransi</th>
                                <th class="p-2">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($pola->details as $d)
                                <tr class="{{ $d->is_libur ? 'bg-red-50/20 text-gray-400' : 'text-gray-800' }}">
                                    <td class="p-2 font-bold font-sans">{{ $d->hari }}</td>
                                    <td class="p-2">{{ $d->is_libur ? '-' : $d->jam_masuk . ' - ' . $d->jam_pulang }}</td>
                                    <td class="p-2">{{ $d->is_libur ? '-' : $d->mulai_absen_masuk . ' - ' . $d->akhir_absen_masuk }}</td>
                                    <td class="p-2">{{ $d->is_libur ? '-' : $d->mulai_absen_pulang . ' - ' . $d->akhir_absen_pulang }}</td>
                                    <td class="p-2 font-sans">{{ $d->is_libur ? '-' : $d->toleransi_menit . ' mnt' }}</td>
                                    <td class="p-2 font-sans font-bold {{ $d->is_libur ? 'text-red-500' : 'text-emerald-600' }}">
                                        {{ $d->is_libur ? 'LIBUR' : 'AKTIF' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
