@extends('layouts.admin')
@section('title', 'Admin Jam Kerja Dinamis - ATP')
@section('page_title', 'Jam Kerja')
@section('title_header', 'Pengaturan Jam Kerja Dinamis')
@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-xl shadow-xs border border-gray-200 p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
            <h2 class="text-base font-bold text-gray-900">Pola Jadwal & Toleransi Kehadiran</h2>
            <p class="text-xs text-gray-500">Konfigurasi pola 5 hari kantor, 6 hari sekolah/puskesmas, ramadhan, dan shift dinamis.</p>
        </div>
        <a href="{{ route('jam-kerja.create') }}" class="inline-flex items-center space-x-1.5 bg-[#1877F2] hover:bg-[#166FE5] text-white font-bold px-3.5 py-2 rounded-lg text-xs shadow-xs transition">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tambah Pola</span>
        </a>
    </div>

    <div class="space-y-4">
        @foreach($polas as $pola)
            <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <div class="flex items-center flex-wrap gap-1.5">
                        <span class="font-bold text-sm text-gray-900">{{ $pola->nama }}</span>
                        @if($pola->is_default)
                            <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full">DEFAULT</span>
                        @endif
                        <span class="bg-blue-50 text-[#1877F2] border border-blue-200 text-[10px] font-semibold px-2 py-0.5 rounded-full">{{ $pola->tipe }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('jam-kerja.edit', $pola->id) }}" class="text-xs font-semibold bg-white border border-gray-300 px-3 py-1.5 rounded-lg hover:border-[#1877F2] hover:text-[#1877F2] transition">
                            <i class="fa-solid fa-pen-to-square mr-1"></i>Edit
                        </a>
                        @if(!$pola->is_default)
                            <form action="{{ route('jam-kerja.destroy', $pola->id) }}" method="POST" onsubmit="return confirm('Hapus pola ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-semibold bg-white border border-red-200 text-red-600 px-3 py-1.5 rounded-lg hover:bg-red-50 transition">
                                    <i class="fa-solid fa-trash-can mr-1"></i>Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-mono min-w-[550px]">
                        <thead class="bg-gray-50/80 text-gray-500 text-[10px] uppercase font-sans border-b">
                            <tr>
                                <th class="p-3">Hari</th>
                                <th class="p-3">Jam Kerja</th>
                                <th class="p-3">Absen Masuk</th>
                                <th class="p-3">Absen Pulang</th>
                                <th class="p-3">Toleransi</th>
                                <th class="p-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($pola->details as $d)
                                <tr class="{{ $d->is_libur ? 'bg-red-50/20 text-gray-400' : 'text-gray-800 hover:bg-blue-50/30' }}">
                                    <td class="p-3 font-bold font-sans">{{ $d->hari }}</td>
                                    <td class="p-3">{{ $d->is_libur ? '-' : $d->jam_masuk . ' - ' . $d->jam_pulang }}</td>
                                    <td class="p-3">{{ $d->is_libur ? '-' : $d->mulai_absen_masuk . ' - ' . $d->akhir_absen_masuk }}</td>
                                    <td class="p-3">{{ $d->is_libur ? '-' : $d->mulai_absen_pulang . ' - ' . $d->akhir_absen_pulang }}</td>
                                    <td class="p-3 font-sans">{{ $d->is_libur ? '-' : $d->toleransi_menit . ' mnt' }}</td>
                                    <td class="p-3 font-sans font-bold {{ $d->is_libur ? 'text-red-500' : 'text-emerald-600' }}">
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
