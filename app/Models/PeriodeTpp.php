<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeTpp extends Model
{
    use HasFactory;

    protected $table = 'periode_tpp';

    protected $fillable = [
        'bulan',
        'tahun',
        'status',
        'locked_at',
        'locked_by',
    ];

    protected $casts = [
        'bulan' => 'integer',
        'tahun' => 'integer',
        'locked_at' => 'datetime',
    ];

    public function perhitungans()
    {
        return $this->hasMany(TppPerhitungan::class);
    }

    public function pajakBulanan()
    {
        return $this->hasMany(PajakAsnBulanan::class);
    }

    public function lockedBy()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }
}
