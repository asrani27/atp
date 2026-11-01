<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifPajakTer extends Model
{
    use HasFactory;

    protected $table = 'tarif_pajak_ter';

    protected $fillable = [
        'kategori',
        'penghasilan_bruto_min',
        'penghasilan_bruto_max',
        'tarif_persen',
    ];

    protected $casts = [
        'penghasilan_bruto_min' => 'decimal:2',
        'penghasilan_bruto_max' => 'decimal:2',
        'tarif_persen' => 'decimal:2',
    ];

    /**
     * Hitung tarif TER berdasarkan kategori dan total bruto
     */
    public static function getTarif(string $kategori, float $totalBruto): float
    {
        $tier = static::where('kategori', $kategori)
            ->where('penghasilan_bruto_min', '<=', $totalBruto)
            ->where(function ($query) use ($totalBruto) {
                $query->whereNull('penghasilan_bruto_max')
                      ->orWhere('penghasilan_bruto_max', '>=', $totalBruto);
            })
            ->first();

        return $tier ? (float)$tier->tarif_persen : 0.0;
    }
}
