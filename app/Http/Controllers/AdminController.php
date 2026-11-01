<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Skpd;
use App\Models\PolaJamKerja;
use App\Models\LokasiPresensi;
use App\Models\Presensi;
use App\Models\Aktivitas;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();
        
        $stats = [
            'totalPegawai' => Pegawai::count(),
            'totalSkpd' => Skpd::count(),
            'totalPola' => PolaJamKerja::count(),
            'totalLokasi' => LokasiPresensi::count(),
            'presensiHariIni' => Presensi::where('tanggal', $today)->count(),
            'aktivitasPending' => Aktivitas::where('status_validasi', 'SUBMITTED')->count(),
        ];

        $latestPresensi = Presensi::with(['pegawai.user', 'lokasiMasuk'])
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        $polas = PolaJamKerja::with('details')->get();

        return view('admin.dashboard', compact('stats', 'latestPresensi', 'polas'));
    }

    public function lokasiIndex()
    {
        $lokasis = LokasiPresensi::with('skpd')->get();
        return view('admin.lokasi', compact('lokasis'));
    }

    public function pegawaiIndex()
    {
        $pegawais = Pegawai::with(['user', 'skpd', 'unitKerja', 'jabatan'])->paginate(15);
        return view('admin.pegawai', compact('pegawais'));
    }
}
