<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pegawai;
use App\Models\Presensi;
use App\Models\Aktivitas;
use App\Models\PolaJamKerja;
use App\Models\Skpd;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();
        $user = Auth::user();

        $stats = [
            'total_pegawai' => Pegawai::where('is_aktif', true)->count(),
            'total_skpd' => Skpd::where('is_aktif', true)->count(),
            'presensi_today' => Presensi::where('tanggal', $today)->count(),
            'hadir_today' => Presensi::where('tanggal', $today)->where('status_kehadiran', 'HADIR')->count(),
            'terlambat_today' => Presensi::where('tanggal', $today)->where('status_kehadiran', 'TERLAMBAT')->count(),
            'aktivitas_month' => Aktivitas::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count(),
            'pola_jam_kerja' => PolaJamKerja::where('is_aktif', true)->count(),
        ];

        // Status presensi user hari ini
        $myPresensi = null;
        if ($user->pegawai) {
            $myPresensi = Presensi::where('pegawai_id', $user->pegawai->id)
                ->where('tanggal', $today)
                ->first();
        }

        return view('portal.dashboard', compact('stats', 'myPresensi'));
    }
}
