<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PajakAsnBulanan extends Model
{
    use HasFactory;

    protected $table = 'pajak_asn_bulanan';

    protected $fillable = [
        'periode_tpp_id',
        'pegawai_id',
        'skpd_id',
        'gaji_pokok_tunjangan',
        'nominal_tpp',
        'total_bruto',
        'status_ptkp',
        'kategori_ter',
        'tarif_ter_persen',
        'pph21_terhutang',
        'dasar_potongan_bpjs',
        'bpjs_pegawai_1persen',
        'bpjs_pemda_4persen',
        'is_tervalidasi',
    ];

    protected $casts = [
        'gaji_pokok_tunjangan' => 'decimal:2',
        'nominal_tpp' => 'decimal:2',
        'total_bruto' => 'decimal:2',
        'tarif_ter_persen' => 'decimal:2',
        'pph21_terhutang' => 'decimal:2',
        'dasar_potongan_bpjs' => 'decimal:2',
        'bpjs_pegawai_1persen' => 'decimal:2',
        'bpjs_pemda_4persen' => 'decimal:2',
        'is_tervalidasi' => 'boolean',
    ];

    public function periodeTpp()
    {
        return $this->belongsTo(PeriodeTpp::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function skpd()
    {
        return $this->belongsTo(Skpd::class);
    }
}
