<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skp extends Model
{
    use HasFactory;

    protected $table = 'skp';

    protected $fillable = [
        'pegawai_id',
        'tahun',
        'periode_mulai',
        'periode_selesai',
        'pendekatan',
        'status',
        'pejabat_penilai_id',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function pejabatPenilai()
    {
        return $this->belongsTo(Pegawai::class, 'pejabat_penilai_id');
    }

    public function rhks()
    {
        return $this->hasMany(SkpRhk::class);
    }
}
