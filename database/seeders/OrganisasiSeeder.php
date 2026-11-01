<?php

namespace Database\Seeders;

use App\Models\Skpd;
use App\Models\UnitKerja;
use App\Models\Pangkat;
use App\Models\Eselon;
use App\Models\Jabatan;
use App\Models\LokasiPresensi;
use Illuminate\Database\Seeder;

class OrganisasiSeeder extends Seeder
{
    public function run(): void
    {
        $bkpsdm = Skpd::firstOrCreate(
            ['kode' => 'BKPSDM-01'],
            [
                'nama' => 'Badan Kepegawaian & Pengembangan SDM',
                'singkatan' => 'BKPSDM',
                'alamat' => 'Jl. Pangeran Hidayatullah No. 10',
                'latitude' => -3.441544,
                'longitude' => 114.843232,
                'radius_meter' => 150,
                'is_aktif' => true,
            ]
        );

        $dinkes = Skpd::firstOrCreate(
            ['kode' => 'DINKES-02'],
            [
                'nama' => 'Dinas Kesehatan',
                'singkatan' => 'DINKES',
                'alamat' => 'Jl. Menteri Empat No. 45',
                'latitude' => -3.435210,
                'longitude' => 114.839810,
                'radius_meter' => 120,
                'is_aktif' => true,
            ]
        );

        $puskesmas = UnitKerja::firstOrCreate(
            ['nama' => 'Puskesmas Martapura 1', 'skpd_id' => $dinkes->id],
            [
                'jenis' => 'PUSKESMAS',
                'latitude' => -3.428900,
                'longitude' => 114.848200,
                'radius_meter' => 100,
                'is_aktif' => true,
            ]
        );

        LokasiPresensi::firstOrCreate(
            ['nama' => 'Kantor Utama BKPSDM'],
            [
                'skpd_id' => $bkpsdm->id,
                'latitude' => -3.441544,
                'longitude' => 114.843232,
                'radius_meter' => 150,
                'is_aktif' => true,
            ]
        );

        LokasiPresensi::firstOrCreate(
            ['nama' => 'Kantor Dinas Kesehatan'],
            [
                'skpd_id' => $dinkes->id,
                'latitude' => -3.435210,
                'longitude' => 114.839810,
                'radius_meter' => 120,
                'is_aktif' => true,
            ]
        );

        $pangkat3a = Pangkat::firstOrCreate(['golongan' => 'III/a'], ['nama' => 'Penata Muda']);
        $pangkat3b = Pangkat::firstOrCreate(['golongan' => 'III/b'], ['nama' => 'Penata Muda Tk. I']);
        $pangkat4a = Pangkat::firstOrCreate(['golongan' => 'IV/a'], ['nama' => 'Pembina']);

        $eselon2b = Eselon::firstOrCreate(['nama' => 'II.b']);
        $nonEselon = Eselon::firstOrCreate(['nama' => 'Non Eselon']);

        $jabatanKadis = Jabatan::firstOrCreate(
            ['nama' => 'Kepala Badan Kepegawaian', 'skpd_id' => $bkpsdm->id],
            [
                'jenis' => 'STRUKTURAL',
                'kelas' => 14,
                'pagu_beban_kerja' => 7500000,
                'pagu_prestasi_kerja' => 5000000,
                'pagu_kondisi_kerja' => 1000000,
                'pagu_kelangkaan_profesi' => 0,
                'pagu_total' => 13500000,
                'is_kadis' => true,
            ]
        );

        Jabatan::firstOrCreate(
            ['nama' => 'Pranata Komputer Ahli Pertama', 'skpd_id' => $bkpsdm->id],
            [
                'jenis' => 'JFT',
                'kelas' => 8,
                'pagu_beban_kerja' => 2500000,
                'pagu_prestasi_kerja' => 1800000,
                'pagu_kondisi_kerja' => 500000,
                'pagu_kelangkaan_profesi' => 0,
                'pagu_total' => 4800000,
                'atasan_id' => $jabatanKadis->id,
            ]
        );

        Jabatan::firstOrCreate(
            ['nama' => 'Perawat Ahli Pertama', 'skpd_id' => $dinkes->id],
            [
                'unit_kerja_id' => $puskesmas->id,
                'jenis' => 'NAKES',
                'kelas' => 8,
                'pagu_beban_kerja' => 2700000,
                'pagu_prestasi_kerja' => 1700000,
                'pagu_kondisi_kerja' => 600000,
                'pagu_kelangkaan_profesi' => 0,
                'pagu_total' => 5000000,
            ]
        );
    }
}
