@extends('layouts.app')
@section('title', 'Edit Pola Jam Kerja - ATP')
@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex justify-between items-center">
        <div>
            <h1 class="text-base font-bold text-gray-900">Edit Pola Jam Kerja: {{ $jam_kerja->nama }}</h1>
            <p class="text-xs text-gray-500">Sesuaikan aturan jam masuk, jam pulang, dan batas toleransi.</p>
        </div>
        <a href="{{ route('jam-kerja.index') }}" class="text-xs text-gray-600 hover:text-gray-900">&larr; Kembali</a>
    </div>

    <form action="{{ route('jam-kerja.update', $jam_kerja->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
            <div class="md:col-span-2">
                <label class="block font-bold mb-1">Nama Pola</label>
                <input type="text" name="nama" required value="{{ $jam_kerja->nama }}" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block font-bold mb-1">Tipe Pola</label>
                <select name="tipe" required class="w-full border rounded p-2 bg-white">
                    <option value="REGULER_5" {{ $jam_kerja->tipe === 'REGULER_5' ? 'selected' : '' }}>Reguler 5 Hari</option>
                    <option value="REGULER_6" {{ $jam_kerja->tipe === 'REGULER_6' ? 'selected' : '' }}>Reguler 6 Hari</option>
                    <option value="RAMADHAN_5" {{ $jam_kerja->tipe === 'RAMADHAN_5' ? 'selected' : '' }}>Ramadhan 5 Hari</option>
                    <option value="SHIFT" {{ $jam_kerja->tipe === 'SHIFT' ? 'selected' : '' }}>Pola Shift</option>
                </select>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 space-y-3">
            <h2 class="text-xs font-bold uppercase text-gray-700">Jadwal Per Hari</h2>
            <div class="space-y-2 text-xs">
                @foreach($days as $idx => $day)
                    @php $detail = $detailsByDay->get($day); @endphp
                    <div class="border rounded p-2.5 bg-gray-50/50 flex flex-wrap items-center gap-2">
                        <span class="w-20 font-bold text-gray-800">{{ $day }}</span>
                        <input type="hidden" name="details[{{ $idx }}][hari]" value="{{ $day }}">
                        
                        <div class="flex items-center space-x-1">
                            <span class="text-gray-400">Masuk:</span>
                            <input type="time" name="details[{{ $idx }}][jam_masuk]" value="{{ $detail ? substr($detail->jam_masuk, 0, 5) : '07:30' }}" class="border rounded p-1 bg-white font-mono">
                            <span class="text-gray-400">Pulang:</span>
                            <input type="time" name="details[{{ $idx }}][jam_pulang]" value="{{ $detail ? substr($detail->jam_pulang, 0, 5) : '16:00' }}" class="border rounded p-1 bg-white font-mono">
                        </div>

                        <input type="hidden" name="details[{{ $idx }}][mulai_absen_masuk]" value="{{ $detail ? $detail->mulai_absen_masuk : '06:00:00' }}">
                        <input type="hidden" name="details[{{ $idx }}][akhir_absen_masuk]" value="{{ $detail ? $detail->akhir_absen_masuk : '09:00:00' }}">
                        <input type="hidden" name="details[{{ $idx }}][mulai_absen_pulang]" value="{{ $detail ? $detail->mulai_absen_pulang : '15:30:00' }}">
                        <input type="hidden" name="details[{{ $idx }}][akhir_absen_pulang]" value="{{ $detail ? $detail->akhir_absen_pulang : '20:00:00' }}">

                        <div class="flex items-center space-x-1">
                            <span class="text-gray-400">Toleransi:</span>
                            <input type="number" name="details[{{ $idx }}][toleransi_menit]" value="{{ $detail ? $detail->toleransi_menit : 15 }}" class="w-14 border rounded p-1 bg-white"> mnt
                        </div>

                        <label class="ml-auto text-xs flex items-center space-x-1 text-gray-600">
                            <input type="checkbox" name="details[{{ $idx }}][is_libur]" value="1" {{ ($detail && $detail->is_libur) ? 'checked' : '' }}>
                            <span>Libur</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="w-full bg-[#1877F2] hover:bg-[#166FE5] text-white font-bold py-2.5 rounded text-xs">
            Perbarui Pola Jam Kerja
        </button>
    </form>
</div>
@endsection
