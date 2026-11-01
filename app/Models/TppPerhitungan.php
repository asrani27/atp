<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TppPerhitungan extends Model
{
    use HasFactory;

    protected $table = 'tpp_perhitungan';

    protected $fillable = [
        'periode_tpp_id',
        'pegawai_id',
        'skpd_id',
        'pagu_beban_kerja',
        'pagu_prestasi_kerja',
        'pagu_kondisi_kerja',
        'pagu_kelangkaan_profesi',
        'pagu_total',
        'target_menit_kinerja',
        'capaian_menit_kinerja',
        'persen_produktivitas',
        'nominal_produktivitas',
        'total_hari_kerja',
        'jumlah_hadir',
        'jumlah_terlambat',
        'jumlah_pulang_cepat',
        'jumlah_alpa',
        'persen_potongan_disiplin',
        'persen_disiplin',
        'nominal_disiplin',
        'persen_sanksi',
        'nominal_sanksi',
        'tpp_kotor',
        'potongan_bpjs_1persen',
        'iuran_bpjs_4persen',
        'potongan_pph21',
        'tpp_bersih',
    ];

    protected $casts = [
        'pagu_beban_kerja' => 'decimal:2',
        'pagu_prestasi_kerja' => 'decimal:2',
        'pagu_kondisi_kerja' => 'decimal:2',
        'pagu_kelangkaan_profesi' => 'decimal:2',
        'pagu_total' => 'decimal:2',
        'target_menit_kinerja' => 'integer',
        'capaian_menit_kinerja' => 'integer',
        'persen_produktivitas' => 'decimal:2',
        'nominal_produktivitas' => 'decimal:2',
        'total_hari_kerja' => 'integer',
        'jumlah_hadir' => 'integer',
        'jumlah_terlambat' => 'integer',
        'jumlah_pulang_cepat' => 'integer',
        'jumlah_alpa' => 'integer',
        'persen_potongan_disiplin' => 'decimal:2',
        'persen_disiplin' => 'decimal:2',
        'nominal_disiplin' => 'decimal:2',
        'persen_sanksi' => 'decimal:2',
        'nominal_sanksi' => 'decimal:2',
        'tpp_kotor' => 'decimal:2',
        'potongan_bpjs_1persen' => 'decimal:2',
        'iuran_bpjs_4persen' => 'decimal:2',
        'potongan_pph21' => 'decimal:2',
        'tpp_bersih' => 'decimal:2',
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
