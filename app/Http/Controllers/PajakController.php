<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\TarifPajakTer;
use App\Models\PajakAsnBulanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PajakController extends Controller
{
    public function index()
    {
        $pegawais = Pegawai::with(['skpd', 'jabatan'])->where('is_aktif', true)->get();
        return view('pajak.index', compact('pegawais'));
    }

    public function simulator(Request $request)
    {
        $gaji = (float)$request->get('gaji', 3800000);
        $tpp  = (float)$request->get('tpp', 4800000);
        $ptkp = $request->get('ptkp', 'K/1');

        // Kategori TER berdasarkan PTKP
        $kategori = 'TER_A';
        if (in_array($ptkp, ['TK/2', 'TK/3', 'K/1', 'K/2'])) {
            $kategori = 'TER_B';
        } elseif ($ptkp === 'K/3') {
            $kategori = 'TER_C';
        }

        $totalBruto = $gaji + $tpp;
        $tarifPersen = TarifPajakTer::getTarif($kategori, $totalBruto);
        $pph21 = round(($totalBruto * $tarifPersen) / 100, 2);

        // BPJS Kesehatan (Maksimal batas atas dasar pengenaan 12.000.000)
        $dasarBpjs = min(12000000, $gaji);
        $bpjsPegawai1 = round($dasarBpjs * 0.01, 2);
        $bpjsPemda4 = round($dasarBpjs * 0.04, 2);

        $result = [
            'gaji' => $gaji,
            'tpp' => $tpp,
            'ptkp' => $ptkp,
            'kategori' => $kategori,
            'total_bruto' => $totalBruto,
            'tarif_persen' => $tarifPersen,
            'pph21' => $pph21,
            'bpjs_pegawai' => $bpjsPegawai1,
            'bpjs_pemda' => $bpjsPemda4,
            'netto' => $totalBruto - $pph21 - $bpjsPegawai1,
        ];

        return view('pajak.simulator', compact('result'));
    }

    public function tarif(Request $request)
    {
        $kategori = $request->get('kategori', 'TER_A');
        $tarifs = TarifPajakTer::where('kategori', $kategori)->orderBy('penghasilan_bruto_min')->get();
        return view('pajak.tarif', compact('tarifs', 'kategori'));
    }
}
