<?php

namespace Database\Seeders;

use App\Models\PolaJamKerja;
use App\Models\JamKerjaDetail;
use Illuminate\Database\Seeder;

class JamKerjaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pola 5 Hari Kerja (Reguler)
        $pola5 = PolaJamKerja::firstOrCreate(
            ['nama' => 'Pola 5 Hari Kerja (Reguler Kantor)'],
            [
                'tipe' => 'REGULER_5',
                'is_aktif' => true,
                'is_default' => true,
                'keterangan' => 'Jam kerja standar ASN Pemda 5 hari kerja (Senin - Jumat)',
            ]
        );

        $days5 = ['SENIN', 'SELASA', 'RABU', 'KAMIS'];
        foreach ($days5 as $day) {
            JamKerjaDetail::firstOrCreate(
                ['pola_jam_kerja_id' => $pola5->id, 'hari' => $day],
                [
                    'jam_masuk' => '07:30:00',
                    'jam_pulang' => '16:00:00',
                    'mulai_absen_masuk' => '06:00:00',
                    'akhir_absen_masuk' => '09:00:00',
                    'mulai_absen_pulang' => '15:30:00',
                    'akhir_absen_pulang' => '20:00:00',
                    'toleransi_menit' => 15,
                    'is_libur' => false,
                ]
            );
        }

        // Jumat
        JamKerjaDetail::firstOrCreate(
            ['pola_jam_kerja_id' => $pola5->id, 'hari' => 'JUMAT'],
            [
                'jam_masuk' => '07:30:00',
                'jam_pulang' => '16:30:00',
                'mulai_absen_masuk' => '06:00:00',
                'akhir_absen_masuk' => '09:00:00',
                'mulai_absen_pulang' => '16:00:00',
                'akhir_absen_pulang' => '20:00:00',
                'toleransi_menit' => 15,
                'is_libur' => false,
            ]
        );

        // Sabtu & Minggu Libur
        foreach (['SABTU', 'MINGGU'] as $day) {
            JamKerjaDetail::firstOrCreate(
                ['pola_jam_kerja_id' => $pola5->id, 'hari' => $day],
                [
                    'jam_masuk' => '00:00:00',
                    'jam_pulang' => '00:00:00',
                    'mulai_absen_masuk' => '00:00:00',
                    'akhir_absen_masuk' => '00:00:00',
                    'mulai_absen_pulang' => '00:00:00',
                    'akhir_absen_pulang' => '00:00:00',
                    'toleransi_menit' => 0,
                    'is_libur' => true,
                ]
            );
        }

        // 2. Pola 6 Hari Kerja (Puskesmas / Sekolah)
        $pola6 = PolaJamKerja::firstOrCreate(
            ['nama' => 'Pola 6 Hari Kerja (Sekolah / Puskesmas)'],
            [
                'tipe' => 'REGULER_6',
                'is_aktif' => true,
                'is_default' => false,
                'keterangan' => 'Jam kerja untuk unit pelayanan 6 hari kerja (Senin - Sabtu)',
            ]
        );

        foreach (['SENIN', 'SELASA', 'RABU', 'KAMIS'] as $day) {
            JamKerjaDetail::firstOrCreate(
                ['pola_jam_kerja_id' => $pola6->id, 'hari' => $day],
                [
                    'jam_masuk' => '07:30:00',
                    'jam_pulang' => '14:00:00',
                    'mulai_absen_masuk' => '06:00:00',
                    'akhir_absen_masuk' => '08:30:00',
                    'mulai_absen_pulang' => '13:45:00',
                    'akhir_absen_pulang' => '18:00:00',
                    'toleransi_menit' => 15,
                    'is_libur' => false,
                ]
            );
        }

        JamKerjaDetail::firstOrCreate(
            ['pola_jam_kerja_id' => $pola6->id, 'hari' => 'JUMAT'],
            [
                'jam_masuk' => '07:30:00',
                'jam_pulang' => '11:30:00',
                'mulai_absen_masuk' => '06:00:00',
                'akhir_absen_masuk' => '08:30:00',
                'mulai_absen_pulang' => '11:15:00',
                'akhir_absen_pulang' => '15:00:00',
                'toleransi_menit' => 15,
                'is_libur' => false,
            ]
        );

        JamKerjaDetail::firstOrCreate(
            ['pola_jam_kerja_id' => $pola6->id, 'hari' => 'SABTU'],
            [
                'jam_masuk' => '07:30:00',
                'jam_pulang' => '13:00:00',
                'mulai_absen_masuk' => '06:00:00',
                'akhir_absen_masuk' => '08:30:00',
                'mulai_absen_pulang' => '12:45:00',
                'akhir_absen_pulang' => '16:00:00',
                'toleransi_menit' => 15,
                'is_libur' => false,
            ]
        );

        JamKerjaDetail::firstOrCreate(
            ['pola_jam_kerja_id' => $pola6->id, 'hari' => 'MINGGU'],
            [
                'jam_masuk' => '00:00:00',
                'jam_pulang' => '00:00:00',
                'mulai_absen_masuk' => '00:00:00',
                'akhir_absen_masuk' => '00:00:00',
                'mulai_absen_pulang' => '00:00:00',
                'akhir_absen_pulang' => '00:00:00',
                'toleransi_menit' => 0,
                'is_libur' => true,
            ]
        );
    }
}
