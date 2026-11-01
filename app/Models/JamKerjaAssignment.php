<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamKerjaAssignment extends Model
{
    use HasFactory;

    protected $table = 'jam_kerja_assignment';

    protected $fillable = [
        'pola_jam_kerja_id',
        'skpd_id',
        'unit_kerja_id',
        'pegawai_id',
        'berlaku_mulai',
        'berlaku_sampai',
    ];

    protected $casts = [
        'berlaku_mulai' => 'date',
        'berlaku_sampai' => 'date',
    ];

    public function polaJamKerja()
    {
        return $this->belongsTo(PolaJamKerja::class);
    }

    public function skpd()
    {
        return $this->belongsTo(Skpd::class);
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
