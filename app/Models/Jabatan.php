<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatan';

    protected $fillable = [
        'skpd_id',
        'unit_kerja_id',
        'nama',
        'jenis',
        'kelas',
        'pagu_beban_kerja',
        'pagu_prestasi_kerja',
        'pagu_kondisi_kerja',
        'pagu_kelangkaan_profesi',
        'pagu_total',
        'atasan_id',
        'is_sekda',
        'is_kadis',
        'is_aktif',
    ];

    protected $casts = [
        'kelas' => 'integer',
        'pagu_beban_kerja' => 'decimal:2',
        'pagu_prestasi_kerja' => 'decimal:2',
        'pagu_kondisi_kerja' => 'decimal:2',
        'pagu_kelangkaan_profesi' => 'decimal:2',
        'pagu_total' => 'decimal:2',
        'is_sekda' => 'boolean',
        'is_kadis' => 'boolean',
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

    public function atasan()
    {
        return $this->belongsTo(Jabatan::class, 'atasan_id');
    }

    public function bawahan()
    {
        return $this->hasMany(Jabatan::class, 'atasan_id');
    }

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }
}
