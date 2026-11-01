<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Skpd;
use App\Models\UnitKerja;
use App\Models\Pangkat;
use App\Models\Eselon;
use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAndPegawaiSeeder extends Seeder
{
    public function run(): void
    {
        $bkpsdm = Skpd::where('kode', 'BKPSDM-01')->first();
        $dinkes = Skpd::where('kode', 'DINKES-02')->first();
        $puskesmas = UnitKerja::where('nama', 'Puskesmas Martapura 1')->first();
        $pangkat3a = Pangkat::where('golongan', 'III/a')->first();
        $pangkat3b = Pangkat::where('golongan', 'III/b')->first();
        $nonEselon = Eselon::where('nama', 'Non Eselon')->first();
        $jabatanPranata = Jabatan::where('nama', 'Pranata Komputer Ahli Pertama')->first();
        $jabatanPerawat = Jabatan::where('nama', 'Perawat Ahli Pertama')->first();

        // 1. Super Administrator
        $superUser = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Super Administrator ATP',
                'email' => 'admin@pemda.go.id',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );
        $superUser->syncRoles(['superadmin']);

        // 2. Admin SKPD BKPSDM
        $adminSkpdUser = User::firstOrCreate(
            ['username' => 'adminskpd'],
            [
                'name' => 'Admin SKPD BKPSDM',
                'email' => 'adminskpd@pemda.go.id',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );
        $adminSkpdUser->syncRoles(['admin_skpd']);

        // 3. Pegawai ASN 1 (Ahmad Fauzi)
        $asnUser1 = User::firstOrCreate(
            ['username' => '198501152010011012'],
            [
                'name' => 'Ahmad Fauzi, S.Kom',
                'email' => 'fauzi@pemda.go.id',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );
        $asnUser1->syncRoles(['pegawai', 'validator']);

        Pegawai::firstOrCreate(
            ['nip' => '198501152010011012'],
            [
                'user_id' => $asnUser1->id,
                'nama' => 'Ahmad Fauzi',
                'gelar_belakang' => 'S.Kom',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Banjarmasin',
                'tanggal_lahir' => '1985-01-15',
                'skpd_id' => $bkpsdm->id,
                'jabatan_id' => $jabatanPranata->id,
                'pangkat_id' => $pangkat3b->id,
                'eselon_id' => $nonEselon->id,
                'status_kepegawaian' => 'PNS',
                'npwp' => '72.123.456.7-731.000',
                'status_ptkp' => 'K/1',
                'jumlah_tanggungan' => 1,
                'gaji_pokok' => 3800000,
                'nama_bank' => 'BANK KALSEL',
                'no_rekening' => '001020304050',
                'is_aktif' => true,
            ]
        );

        // 4. Pegawai ASN 2 (Siti Rahmah)
        $asnUser2 = User::firstOrCreate(
            ['username' => '199008202015032005'],
            [
                'name' => 'Siti Rahmah, S.Kep',
                'email' => 'siti.rahmah@pemda.go.id',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );
        $asnUser2->syncRoles(['pegawai']);

        Pegawai::firstOrCreate(
            ['nip' => '199008202015032005'],
            [
                'user_id' => $asnUser2->id,
                'nama' => 'Siti Rahmah',
                'gelar_belakang' => 'S.Kep',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Martapura',
                'tanggal_lahir' => '1990-08-20',
                'skpd_id' => $dinkes->id,
                'unit_kerja_id' => $puskesmas ? $puskesmas->id : null,
                'jabatan_id' => $jabatanPerawat->id,
                'pangkat_id' => $pangkat3a->id,
                'eselon_id' => $nonEselon->id,
                'status_kepegawaian' => 'PNS',
                'npwp' => '85.987.654.3-731.000',
                'status_ptkp' => 'TK/0',
                'jumlah_tanggungan' => 0,
                'gaji_pokok' => 3200000,
                'nama_bank' => 'BANK KALSEL',
                'no_rekening' => '001099887766',
                'is_aktif' => true,
            ]
        );
    }
}
