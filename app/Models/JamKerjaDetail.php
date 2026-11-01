<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamKerjaDetail extends Model
{
    use HasFactory;

    protected $table = 'jam_kerja_detail';

    protected $fillable = [
        'pola_jam_kerja_id',
        'hari',
        'jam_masuk',
        'jam_pulang',
        'mulai_absen_masuk',
        'akhir_absen_masuk',
        'mulai_absen_pulang',
        'akhir_absen_pulang',
        'toleransi_menit',
        'is_libur',
    ];

    protected $casts = [
        'toleransi_menit' => 'integer',
        'is_libur' => 'boolean',
    ];

    public function polaJamKerja()
    {
        return $this->belongsTo(PolaJamKerja::class);
    }
}
