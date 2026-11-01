<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aktivitas extends Model
{
    use HasFactory;

    protected $table = 'aktivitas';

    protected $fillable = [
        'pegawai_id',
        'skp_rhk_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'durasi_menit',
        'uraian',
        'file_bukti',
        'status_validasi',
        'validator_id',
        'validated_at',
        'catatan_validator',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'durasi_menit' => 'integer',
        'validated_at' => 'datetime',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function skpRhk()
    {
        return $this->belongsTo(SkpRhk::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validator_id');
    }
}
