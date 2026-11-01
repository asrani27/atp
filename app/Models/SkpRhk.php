<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkpRhk extends Model
{
    use HasFactory;

    protected $table = 'skp_rhk';

    protected $fillable = [
        'skp_id',
        'jenis',
        'rencana_kinerja',
        'aspek_kuantitas',
        'aspek_kualitas',
        'aspek_waktu',
    ];

    public function skp()
    {
        return $this->belongsTo(Skp::class);
    }

    public function aktivitas()
    {
        return $this->hasMany(Aktivitas::class);
    }
}
