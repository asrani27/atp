<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $fillable = [
        'user_id',
        'nip',
        'nama',
        'gelar_depan',
        'gelar_belakang',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'skpd_id',
        'unit_kerja_id',
        'jabatan_id',
        'jabatan_plt_id',
        'jabatan_plh_id',
        'pangkat_id',
        'eselon_id',
        'status_kepegawaian',
        'npwp',
        'status_ptkp',
        'jumlah_tanggungan',
        'gaji_pokok',
        'tunjangan_keluarga',
        'tunjangan_jabatan',
        'tunjangan_fungsional',
        'nama_bank',
        'no_rekening',
        'golongan_darah',
        'foto',
        'is_aktif',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'jumlah_tanggungan' => 'integer',
        'gaji_pokok' => 'decimal:2',
        'tunjangan_keluarga' => 'decimal:2',
        'tunjangan_jabatan' => 'decimal:2',
        'tunjangan_fungsional' => 'decimal:2',
        'is_aktif' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skpd()
    {
        return $this->belongsTo(Skpd::class);
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function jabatanPlt()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_plt_id');
    }

    public function pangkat()
    {
        return $this->belongsTo(Pangkat::class);
    }

    public function eselon()
    {
        return $this->belongsTo(Eselon::class);
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }

    public function cuti()
    {
        return $this->hasMany(Cuti::class);
    }

    public function aktivitas()
    {
        return $this->hasMany(Aktivitas::class);
    }

    public function skp()
    {
        return $this->hasMany(Skp::class);
    }

    public function tppPerhitungan()
    {
        return $this->hasMany(TppPerhitungan::class);
    }

    public function pajakAsnBulanan()
    {
        return $this->hasMany(PajakAsnBulanan::class);
    }

    public function getNamaLengkapAttribute(): string
    {
        $namaLengkap = '';
        if (!empty($this->gelar_depan)) {
            $namaLengkap .= trim($this->gelar_depan) . ' ';
        }
        $namaLengkap .= trim($this->nama);
        if (!empty($this->gelar_belakang)) {
            $namaLengkap .= ', ' . trim($this->gelar_belakang);
        }
        return $namaLengkap;
    }
}
