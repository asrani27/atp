<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skpd extends Model
{
    use HasFactory;

    protected $table = 'skpd';

    protected $fillable = [
        'kode',
        'nama',
        'singkatan',
        'alamat',
        'latitude',
        'longitude',
        'radius_meter',
        'is_aktif',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'radius_meter' => 'integer',
        'is_aktif' => 'boolean',
    ];

    public function unitKerja()
    {
        return $this->hasMany(UnitKerja::class);
    }

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }

    public function jabatan()
    {
        return $this->hasMany(Jabatan::class);
    }

    public function lokasiPresensi()
    {
        return $this->hasMany(LokasiPresensi::class);
    }
}
