<?php

namespace App\Http\Controllers;

use App\Models\PolaJamKerja;
use App\Models\JamKerjaDetail;
use Illuminate\Http\Request;

class JamKerjaController extends Controller
{
    public function index()
    {
        $polas = PolaJamKerja::with('details')->orderBy('is_default', 'desc')->get();
        return view('absensi.jam_kerja.index', compact('polas'));
    }

    public function create()
    {
        $days = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU', 'MINGGU'];
        return view('absensi.jam_kerja.create', compact('days'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'tipe' => ['required', 'in:REGULER_5,REGULER_6,SHIFT,RAMADHAN_5,RAMADHAN_6'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date'],
            'is_aktif' => ['nullable', 'boolean'],
            'is_default' => ['nullable', 'boolean'],
            'keterangan' => ['nullable', 'string'],
            'details' => ['required', 'array'],
            'details.*.hari' => ['required', 'in:SENIN,SELASA,RABU,KAMIS,JUMAT,SABTU,MINGGU'],
            'details.*.jam_masuk' => ['required'],
            'details.*.jam_pulang' => ['required'],
            'details.*.mulai_absen_masuk' => ['required'],
            'details.*.akhir_absen_masuk' => ['required'],
            'details.*.mulai_absen_pulang' => ['required'],
            'details.*.akhir_absen_pulang' => ['required'],
            'details.*.toleransi_menit' => ['required', 'integer', 'min:0'],
        ]);

        if (!empty($validated['is_default'])) {
            PolaJamKerja::where('is_default', true)->update(['is_default' => false]);
        }

        $pola = PolaJamKerja::create([
            'nama' => $validated['nama'],
            'tipe' => $validated['tipe'],
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
            'is_aktif' => $request->has('is_aktif'),
            'is_default' => $request->has('is_default'),
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        foreach ($validated['details'] as $detail) {
            JamKerjaDetail::create([
                'pola_jam_kerja_id' => $pola->id,
                'hari' => $detail['hari'],
                'jam_masuk' => $detail['jam_masuk'],
                'jam_pulang' => $detail['jam_pulang'],
                'mulai_absen_masuk' => $detail['mulai_absen_masuk'],
                'akhir_absen_masuk' => $detail['akhir_absen_masuk'],
                'mulai_absen_pulang' => $detail['mulai_absen_pulang'],
                'akhir_absen_pulang' => $detail['akhir_absen_pulang'],
                'toleransi_menit' => $detail['toleransi_menit'],
                'is_libur' => isset($detail['is_libur']),
            ]);
        }

        return redirect()->route('jam-kerja.index')->with('success', 'Pola Jam Kerja berhasil ditambahkan!');
    }

    public function edit(PolaJamKerja $jam_kerja)
    {
        $jam_kerja->load('details');
        $days = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU', 'MINGGU'];
        $detailsByDay = $jam_kerja->details->keyBy('hari');

        return view('absensi.jam_kerja.edit', compact('jam_kerja', 'days', 'detailsByDay'));
    }

    public function update(Request $request, PolaJamKerja $jam_kerja)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'tipe' => ['required', 'in:REGULER_5,REGULER_6,SHIFT,RAMADHAN_5,RAMADHAN_6'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date'],
            'keterangan' => ['nullable', 'string'],
            'details' => ['required', 'array'],
        ]);

        if ($request->has('is_default')) {
            PolaJamKerja::where('id', '!=', $jam_kerja->id)->update(['is_default' => false]);
        }

        $jam_kerja->update([
            'nama' => $validated['nama'],
            'tipe' => $validated['tipe'],
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
            'is_aktif' => $request->has('is_aktif'),
            'is_default' => $request->has('is_default'),
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        foreach ($validated['details'] as $detail) {
            JamKerjaDetail::updateOrCreate(
                [
                    'pola_jam_kerja_id' => $jam_kerja->id,
                    'hari' => $detail['hari'],
                ],
                [
                    'jam_masuk' => $detail['jam_masuk'],
                    'jam_pulang' => $detail['jam_pulang'],
                    'mulai_absen_masuk' => $detail['mulai_absen_masuk'],
                    'akhir_absen_masuk' => $detail['akhir_absen_masuk'],
                    'mulai_absen_pulang' => $detail['mulai_absen_pulang'],
                    'akhir_absen_pulang' => $detail['akhir_absen_pulang'],
                    'toleransi_menit' => $detail['toleransi_menit'],
                    'is_libur' => isset($detail['is_libur']),
                ]
            );
        }

        return redirect()->route('jam-kerja.index')->with('success', 'Pola Jam Kerja berhasil diperbarui!');
    }

    public function destroy(PolaJamKerja $jam_kerja)
    {
        if ($jam_kerja->is_default) {
            return back()->with('error', 'Pola default tidak dapat dihapus!');
        }

        $jam_kerja->delete();
        return redirect()->route('jam-kerja.index')->with('success', 'Pola Jam Kerja berhasil dihapus!');
    }
}
