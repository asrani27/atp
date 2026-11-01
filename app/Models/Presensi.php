<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'pegawai_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'lokasi_masuk_id',
        'lokasi_pulang_id',
        'lat_masuk',
        'long_masuk',
        'lat_pulang',
        'long_pulang',
        'jarak_masuk_meter',
        'jarak_pulang_meter',
        'foto_masuk',
        'foto_pulang',
        'status_kehadiran',
        'menit_keterlambatan',
        'menit_pulang_cepat',
        'is_apel',
        'is_terkunci',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_masuk' => 'datetime',
        'jam_pulang' => 'datetime',
        'lat_masuk' => 'float',
        'long_masuk' => 'float',
        'lat_pulang' => 'float',
        'long_pulang' => 'float',
        'jarak_masuk_meter' => 'decimal:2',
        'jarak_pulang_meter' => 'decimal:2',
        'menit_keterlambatan' => 'integer',
        'menit_pulang_cepat' => 'integer',
        'is_apel' => 'boolean',
        'is_terkunci' => 'boolean',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function lokasiMasuk()
    {
        return $this->belongsTo(LokasiPresensi::class, 'lokasi_masuk_id');
    }

    public function lokasiPulang()
    {
        return $this->belongsTo(LokasiPresensi::class, 'lokasi_pulang_id');
    }
}
