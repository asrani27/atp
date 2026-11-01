<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\Pegawai;
use App\Models\PeriodeTpp;
use App\Models\TppPerhitungan;
use App\Models\Presensi;
use App\Models\TarifPajakTer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TppController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pegawai = $user->pegawai;
        $bulan = now()->month;
        $tahun = now()->year;

        $totalMenitAktivitas = 0;
        $aktivitasPending = 0;

        if ($pegawai) {
            $totalMenitAktivitas = Aktivitas::where('pegawai_id', $pegawai->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->where('status_validasi', 'DISETUJUI')
                ->sum('durasi_menit');

            $aktivitasPending = Aktivitas::where('pegawai_id', $pegawai->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->where('status_validasi', 'PENDING')
                ->count();
        }

        $targetMenit = 6750; // Standar 112.5 jam per bulan
        $persenCapaian = $targetMenit > 0 ? min(100, round(($totalMenitAktivitas / $targetMenit) * 100, 1)) : 0;

        return view('tpp.index', compact('pegawai', 'totalMenitAktivitas', 'targetMenit', 'persenCapaian', 'aktivitasPending'));
    }

    public function aktivitas(Request $request)
    {
        $user = Auth::user();
        $pegawai = $user->pegawai;
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $query = Aktivitas::with('pegawai')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun);

        if ($pegawai && !$user->hasRole(['superadmin', 'admin_skpd'])) {
            $query->where('pegawai_id', $pegawai->id);
        }

        $aktivitasList = $query->orderBy('tanggal', 'desc')->paginate(15);

        return view('tpp.aktivitas.index', compact('aktivitasList', 'bulan', 'tahun'));
    }

    public function storeAktivitas(Request $request)
    {
        $request->validate([
            'tanggal' => ['required', 'date'],
            'jam_mulai' => ['required'],
            'jam_selesai' => ['required'],
            'uraian' => ['required', 'string', 'min:5'],
        ]);

        $pegawai = Auth::user()->pegawai;
        if (!$pegawai) return back()->with('error', 'Data pegawai tidak ditemukan.');

        $mulai = Carbon::parse($request->tanggal . ' ' . $request->jam_mulai);
        $selesai = Carbon::parse($request->tanggal . ' ' . $request->jam_selesai);

        if ($selesai->lessThanOrEqualTo($mulai)) {
            return back()->with('error', 'Jam selesai harus lebih akhir dari jam mulai!');
        }

        $durasi = $mulai->diffInMinutes($selesai);

        Aktivitas::create([
            'pegawai_id' => $pegawai->id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'durasi_menit' => $durasi,
            'uraian' => $request->uraian,
            'status_validasi' => 'PENDING',
        ]);

        return back()->with('success', "Aktivitas berhasil ditambahkan ({$durasi} menit). Menunggu persetujuan verifikator.");
    }

    public function validateAktivitas(Request $request, Aktivitas $aktivitas)
    {
        $request->validate([
            'status' => ['required', 'in:DISETUJUI,DITOLAK'],
            'catatan' => ['nullable', 'string'],
        ]);

        $aktivitas->update([
            'status_validasi' => $request->status,
            'validator_id' => Auth::id(),
            'validated_at' => now(),
            'catatan_validator' => $request->catatan,
        ]);

        return back()->with('success', 'Status aktivitas berhasil diperbarui!');
    }

    public function kalkulasi(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $pegawais = Pegawai::with(['skpd', 'jabatan'])->where('is_aktif', true)->get();

        return view('tpp.kalkulasi', compact('pegawais', 'bulan', 'tahun'));
    }
}
