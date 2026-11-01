<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Periode TPP Bulanan
        Schema::create('periode_tpp', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('bulan'); // 1 - 12
            $table->year('tahun');
            $table->enum('status', ['DRAFT', 'DIPROSES', 'DIKUNCI', 'SELESAI'])->default('DRAFT');
            $table->timestamp('locked_at')->nullable();
            $table->foreignId('locked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['bulan', 'tahun']);
        });

        // 2. Kalkulasi Rekap TPP Pegawai
        Schema::create('tpp_perhitungan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_tpp_id')->constrained('periode_tpp')->cascadeOnDelete();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->foreignId('skpd_id')->constrained('skpd')->cascadeOnDelete();
            
            // Pagu Dasar Jabatan
            $table->decimal('pagu_beban_kerja', 15, 2)->default(0);
            $table->decimal('pagu_prestasi_kerja', 15, 2)->default(0);
            $table->decimal('pagu_kondisi_kerja', 15, 2)->default(0);
            $table->decimal('pagu_kelangkaan_profesi', 15, 2)->default(0);
            $table->decimal('pagu_total', 15, 2)->default(0);

            // Komponen Produktivitas (Kinerja Aktivitas 60%)
            $table->unsignedInteger('target_menit_kinerja')->default(6750); // Standar 112.5 jam/bln
            $table->unsignedInteger('capaian_menit_kinerja')->default(0);
            $table->decimal('persen_produktivitas', 5, 2)->default(0); // Max 60%
            $table->decimal('nominal_produktivitas', 15, 2)->default(0);

            // Komponen Disiplin (Presensi 40%)
            $table->unsignedSmallInteger('total_hari_kerja')->default(22);
            $table->unsignedSmallInteger('jumlah_hadir')->default(0);
            $table->unsignedSmallInteger('jumlah_terlambat')->default(0);
            $table->unsignedSmallInteger('jumlah_pulang_cepat')->default(0);
            $table->unsignedSmallInteger('jumlah_alpa')->default(0);
            $table->decimal('persen_potongan_disiplin', 5, 2)->default(0);
            $table->decimal('persen_disiplin', 5, 2)->default(40.00); // Sisa dari 40% setelah dipotong
            $table->decimal('nominal_disiplin', 15, 2)->default(0);

            // Sanksi & Pengurangan Tambahan
            $table->decimal('persen_sanksi', 5, 2)->default(0);
            $table->decimal('nominal_sanksi', 15, 2)->default(0);

            // Total Kotor (Bruto)
            $table->decimal('tpp_kotor', 15, 2)->default(0);

            // Kewajiban Iuran & Pajak
            $table->decimal('potongan_bpjs_1persen', 15, 2)->default(0);
            $table->decimal('iuran_bpjs_4persen', 15, 2)->default(0);
            $table->decimal('potongan_pph21', 15, 2)->default(0);

            // TPP Diterima Bersih (Netto)
            $table->decimal('tpp_bersih', 15, 2)->default(0);

            $table->timestamps();

            $table->unique(['periode_tpp_id', 'pegawai_id']);
            $table->index(['skpd_id', 'periode_tpp_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tpp_perhitungan');
        Schema::dropIfExists('periode_tpp');
    }
};
