<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Kategori Tarif Efektif Rata-Rata (PP 58/2023)
        Schema::create('tarif_pajak_ter', function (Blueprint $table) {
            $table->id();
            $table->enum('kategori', ['TER_A', 'TER_B', 'TER_C']); // TER A, B, atau C
            $table->decimal('penghasilan_bruto_min', 15, 2);
            $table->decimal('penghasilan_bruto_max', 15, 2)->nullable(); // Nullable untuk layer tak terhingga
            $table->decimal('tarif_persen', 5, 2); // Contoh: 0.00, 0.25, 0.50, ..., 34.00
            $table->timestamps();
            $table->index(['kategori', 'penghasilan_bruto_min']);
        });

        // 2. Rekap Pajak PPh 21 & BPJS Bulanan ASN
        Schema::create('pajak_asn_bulanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_tpp_id')->constrained('periode_tpp')->cascadeOnDelete();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->foreignId('skpd_id')->constrained('skpd')->cascadeOnDelete();
            
            // Komponen Penghasilan
            $table->decimal('gaji_pokok_tunjangan', 15, 2)->default(0);
            $table->decimal('nominal_tpp', 15, 2)->default(0);
            $table->decimal('total_bruto', 15, 2)->default(0);

            // Perhitungan TER
            $table->string('status_ptkp', 10); // TK/0, K/1, dll
            $table->enum('kategori_ter', ['TER_A', 'TER_B', 'TER_C']);
            $table->decimal('tarif_ter_persen', 5, 2)->default(0);
            $table->decimal('pph21_terhutang', 15, 2)->default(0);

            // BPJS Kesehatan (1% Pegawai, 4% Pemberi Kerja)
            $table->decimal('dasar_potongan_bpjs', 15, 2)->default(0);
            $table->decimal('bpjs_pegawai_1persen', 15, 2)->default(0);
            $table->decimal('bpjs_pemda_4persen', 15, 2)->default(0);

            $table->boolean('is_tervalidasi')->default(false);
            $table->timestamps();

            $table->unique(['periode_tpp_id', 'pegawai_id']);
            $table->index(['skpd_id', 'periode_tpp_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pajak_asn_bulanan');
        Schema::dropIfExists('tarif_pajak_ter');
    }
};
