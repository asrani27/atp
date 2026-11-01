<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Permissions
        $permissions = [
            // Presensi & Jam Kerja
            'presensi.view',
            'presensi.absen',
            'presensi.manage',
            'jam_kerja.manage',
            'cuti.request',
            'cuti.approve',

            // TPP & Kinerja
            'aktivitas.view',
            'aktivitas.create',
            'aktivitas.validate',
            'skp.manage',
            'tpp.calculate',
            'tpp.lock',
            'tpp.export',

            // Pajak ASN
            'pajak.view',
            'pajak.manage',
            'pajak.export',

            // User & Master Management
            'users.manage',
            'master.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Define Roles
        $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
        $adminSkpd  = Role::firstOrCreate(['name' => 'admin_skpd']);
        $validator  = Role::firstOrCreate(['name' => 'validator']);
        $pegawai    = Role::firstOrCreate(['name' => 'pegawai']);

        // Superadmin gets everything
        $superadmin->syncPermissions(Permission::all());

        // Admin SKPD
        $adminSkpd->syncPermissions([
            'presensi.view',
            'presensi.manage',
            'cuti.approve',
            'aktivitas.view',
            'tpp.calculate',
            'tpp.export',
            'pajak.view',
            'pajak.manage',
            'pajak.export',
        ]);

        // Validator (Atasan langsung / Pejabat Penilai)
        $validator->syncPermissions([
            'presensi.view',
            'aktivitas.view',
            'aktivitas.validate',
            'cuti.approve',
            'skp.manage',
            'presensi.absen',
            'aktivitas.create',
        ]);

        // Pegawai ASN
        $pegawai->syncPermissions([
            'presensi.view',
            'presensi.absen',
            'cuti.request',
            'aktivitas.view',
            'aktivitas.create',
            'skp.manage',
            'pajak.view',
        ]);
    }
}
