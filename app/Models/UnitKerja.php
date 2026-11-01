<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    use HasFactory;

    protected $table = 'unit_kerja';

    protected $fillable = [
        'skpd_id',
        'nama',
        'jenis',
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

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }
}
