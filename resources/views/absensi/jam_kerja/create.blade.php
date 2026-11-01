@extends('layouts.app')
@section('title', 'Tambah Pola Jam Kerja - ATP')
@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex justify-between items-center">
        <div>
            <h1 class="text-base font-bold text-gray-900">Tambah Pola Jam Kerja</h1>
            <p class="text-xs text-gray-500">Konfigurasi jadwal kerja dan toleransi per hari.</p>
        </div>
        <a href="{{ route('jam-kerja.index') }}" class="text-xs text-gray-600 hover:text-gray-900">&larr; Kembali</a>
    </div>

    <form action="{{ route('jam-kerja.store') }}" method="POST" class="space-y-4">
        @csrf
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
            <div class="md:col-span-2">
                <label class="block font-bold mb-1">Nama Pola</label>
                <input type="text" name="nama" required placeholder="Contoh: Pola Ramadhan 1448 H" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block font-bold mb-1">Tipe Pola</label>
                <select name="tipe" required class="w-full border rounded p-2 bg-white">
                    <option value="REGULER_5">Reguler 5 Hari</option>
                    <option value="REGULER_6">Reguler 6 Hari</option>
                    <option value="RAMADHAN_5">Ramadhan 5 Hari</option>
                    <option value="SHIFT">Pola Shift</option>
                </select>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 space-y-3">
            <h2 class="text-xs font-bold uppercase text-gray-700">Jadwal Per Hari</h2>
            <div class="space-y-2 text-xs">
                @foreach($days as $idx => $day)
                    <div class="border rounded p-2.5 bg-gray-50/50 flex flex-wrap items-center gap-2">
                        <span class="w-20 font-bold text-gray-800">{{ $day }}</span>
                        <input type="hidden" name="details[{{ $idx }}][hari]" value="{{ $day }}">
                        
                        <div class="flex items-center space-x-1">
                            <span class="text-gray-400">Masuk:</span>
                            <input type="time" name="details[{{ $idx }}][jam_masuk]" value="07:30" class="border rounded p-1 bg-white font-mono">
                            <span class="text-gray-400">Pulang:</span>
                            <input type="time" name="details[{{ $idx }}][jam_pulang]" value="{{ in_array($day, ['SABTU','MINGGU']) ? '00:00' : '16:00' }}" class="border rounded p-1 bg-white font-mono">
                        </div>

                        <input type="hidden" name="details[{{ $idx }}][mulai_absen_masuk]" value="06:00:00">
                        <input type="hidden" name="details[{{ $idx }}][akhir_absen_masuk]" value="09:00:00">
                        <input type="hidden" name="details[{{ $idx }}][mulai_absen_pulang]" value="15:30:00">
                        <input type="hidden" name="details[{{ $idx }}][akhir_absen_pulang]" value="20:00:00">

                        <div class="flex items-center space-x-1">
                            <span class="text-gray-400">Toleransi:</span>
                            <input type="number" name="details[{{ $idx }}][toleransi_menit]" value="15" class="w-14 border rounded p-1 bg-white"> mnt
                        </div>

                        <label class="ml-auto text-xs flex items-center space-x-1 text-gray-600">
                            <input type="checkbox" name="details[{{ $idx }}][is_libur]" value="1" {{ in_array($day, ['SABTU','MINGGU']) ? 'checked' : '' }}>
                            <span>Libur</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="w-full bg-[#1877F2] hover:bg-[#166FE5] text-white font-bold py-2.5 rounded text-xs">
            Simpan Pola Jam Kerja
        </button>
    </form>
</div>
@endsection
