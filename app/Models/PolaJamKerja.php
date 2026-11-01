<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolaJamKerja extends Model
{
    use HasFactory;

    protected $table = 'pola_jam_kerja';

    protected $fillable = [
        'nama',
        'tipe',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_aktif',
        'is_default',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_aktif' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function details()
    {
        return $this->hasMany(JamKerjaDetail::class);
    }

    public function assignments()
    {
        return $this->hasMany(JamKerjaAssignment::class);
    }
}
