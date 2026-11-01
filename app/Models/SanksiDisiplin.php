<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanksiDisiplin extends Model
{
    use HasFactory;

    protected $table = 'sanksi_disiplin';

    protected $fillable = [
        'pegawai_id',
        'nomor_sk',
        'tanggal_sk',
        'tingkat_hukuman',
        'jenis_hukuman',
        'mulai_berlaku',
        'selesai_berlaku',
        'persen_potongan_tpp',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_sk' => 'date',
        'mulai_berlaku' => 'date',
        'selesai_berlaku' => 'date',
        'persen_potongan_tpp' => 'decimal:2',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
