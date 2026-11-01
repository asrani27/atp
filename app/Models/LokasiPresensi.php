<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiPresensi extends Model
{
    use HasFactory;

    protected $table = 'lokasi_presensi';

    protected $fillable = [
        'skpd_id',
        'unit_kerja_id',
        'nama',
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

    public function skpd()
    {
        return $this->belongsTo(Skpd::class);
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }
}
