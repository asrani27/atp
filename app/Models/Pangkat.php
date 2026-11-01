<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pangkat extends Model
{
    use HasFactory;

    protected $table = 'pangkat';

    protected $fillable = [
        'nama',
        'golongan',
    ];

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class);
    }
}
