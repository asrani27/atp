<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Pegawai;
use App\Models\LokasiPresensi;
use App\Models\PolaJamKerja;
use App\Models\JamKerjaDetail;
use App\Services\GeoLocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pegawai = $user->pegawai;
        $today = Carbon::today()->toDateString();
        $dayOfWeek = strtoupper(Carbon::today()->locale('id')->isoFormat('dddd'));

        $pola = PolaJamKerja::where('is_default', true)->with('details')->first();
        $jadwalHariIni = $pola ? $pola->details->firstWhere('hari', $dayOfWeek) : null;

        $presensiHariIni = $pegawai
            ? Presensi::where('pegawai_id', $pegawai->id)->where('tanggal', $today)->first()
            : null;

        $lokasis = LokasiPresensi::where('is_aktif', true)->get();

        return view('absensi.index', compact('pegawai', 'jadwalHariIni', 'presensiHariIni', 'lokasis'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'lokasi_id' => ['required', 'exists:lokasi_presensi,id'],
        ]);

        $pegawai = Auth::user()->pegawai;
        if (!$pegawai) return back()->with('error', 'Data pegawai tidak ditemukan.');

        $today = Carbon::today()->toDateString();
        $presensi = Presensi::firstOrNew(['pegawai_id' => $pegawai->id, 'tanggal' => $today]);

        if ($presensi->jam_masuk) {
            return back()->with('error', 'Anda sudah melakukan presensi masuk hari ini!');
        }

        $lokasi = LokasiPresensi::findOrFail($request->lokasi_id);
        $geoCheck = GeoLocationService::isWithinRadius(
            (float)$request->latitude, (float)$request->longitude,
            (float)$lokasi->latitude, (float)$lokasi->longitude,
            $lokasi->radius_meter
        );

        if (!$geoCheck['is_valid']) {
            return back()->with('error', "Presensi Ditolak: Di luar radius ({$geoCheck['distance_meters']}m / max {$lokasi->radius_meter}m).");
        }

        $now = Carbon::now();
        $dayOfWeek = strtoupper($now->locale('id')->isoFormat('dddd'));
        $pola = PolaJamKerja::where('is_default', true)->first();
        $detail = $pola ? JamKerjaDetail::where('pola_jam_kerja_id', $pola->id)->where('hari', $dayOfWeek)->first() : null;

        $status = 'HADIR';
        $menitTerlambat = 0;
        if ($detail) {
            $jamStandar = Carbon::parse($today . ' ' . $detail->jam_masuk)->addMinutes($detail->toleransi_menit);
            if ($now->greaterThan($jamStandar)) {
                $status = 'TERLAMBAT';
                $menitTerlambat = (int) max(0, $jamStandar->diffInMinutes($now));
            }
        }

        $presensi->jam_masuk = $now;
        $presensi->lokasi_masuk_id = $lokasi->id;
        $presensi->lat_masuk = $request->latitude;
        $presensi->long_masuk = $request->longitude;
        $presensi->jarak_masuk_meter = $geoCheck['distance_meters'];
        $presensi->status_kehadiran = $status;
        $presensi->menit_keterlambatan = $menitTerlambat;
        $presensi->save();

        return back()->with('success', "Presensi Masuk Berhasil! Status: {$status}" . ($menitTerlambat > 0 ? " ({$menitTerlambat} mnt terlambat)" : ''));
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'lokasi_id' => ['required', 'exists:lokasi_presensi,id'],
        ]);

        $pegawai = Auth::user()->pegawai;
        $today = Carbon::today()->toDateString();
        $presensi = Presensi::where('pegawai_id', $pegawai->id)->where('tanggal', $today)->first();

        if (!$presensi || !$presensi->jam_masuk) return back()->with('error', 'Anda belum presensi masuk hari ini!');
        if ($presensi->jam_pulang) return back()->with('error', 'Anda sudah presensi pulang hari ini!');

        $lokasi = LokasiPresensi::findOrFail($request->lokasi_id);
        $geoCheck = GeoLocationService::isWithinRadius(
            (float)$request->latitude, (float)$request->longitude,
            (float)$lokasi->latitude, (float)$lokasi->longitude,
            $lokasi->radius_meter
        );

        if (!$geoCheck['is_valid']) {
            return back()->with('error', "Presensi Ditolak: Di luar radius ({$geoCheck['distance_meters']}m).");
        }

        $now = Carbon::now();
        $presensi->jam_pulang = $now;
        $presensi->lokasi_pulang_id = $lokasi->id;
        $presensi->lat_pulang = $request->latitude;
        $presensi->long_pulang = $request->longitude;
        $presensi->jarak_pulang_meter = $geoCheck['distance_meters'];
        $presensi->save();

        return back()->with('success', 'Presensi Pulang Berhasil tercatat pada ' . $now->format('H:i:s'));
    }

    public function riwayat(Request $request)
    {
        $user = Auth::user();
        $pegawai = $user->pegawai;
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $query = Presensi::with(['pegawai', 'lokasiMasuk'])
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun);

        if ($pegawai && !$user->hasRole(['superadmin', 'admin_skpd'])) {
            $query->where('pegawai_id', $pegawai->id);
        }

        $riwayats = $query->orderBy('tanggal', 'desc')->paginate(15);
        return view('absensi.riwayat', compact('riwayats', 'bulan', 'tahun'));
    }
}
