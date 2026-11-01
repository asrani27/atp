<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Pola Jam Kerja (Master Pattern)
        Schema::create('pola_jam_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100); // e.g. "5 Hari Kerja Reguler", "6 Hari Sekolah", "Ramadhan 5 Hari", "Shift 24 Jam"
            $table->enum('tipe', ['REGULER_5', 'REGULER_6', 'SHIFT', 'RAMADHAN_5', 'RAMADHAN_6'])->default('REGULER_5');
            $table->date('tanggal_mulai')->nullable()->comment('Untuk periode khusus seperti Ramadhan');
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->boolean('is_default')->default(false);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 2. Detail Jam Kerja per Hari
        Schema::create('jam_kerja_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pola_jam_kerja_id')->constrained('pola_jam_kerja')->cascadeOnDelete();
            $table->enum('hari', ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU', 'MINGGU']);
            $table->time('jam_masuk');
            $table->time('jam_pulang');
            $table->time('mulai_absen_masuk');
            $table->time('akhir_absen_masuk');
            $table->time('mulai_absen_pulang');
            $table->time('akhir_absen_pulang');
            $table->unsignedSmallInteger('toleransi_menit')->default(0);
            $table->boolean('is_libur')->default(false);
            $table->timestamps();
            $table->unique(['pola_jam_kerja_id', 'hari']);
        });

        // 3. Mapping Penugasan Jam Kerja ke SKPD / Unit Kerja / Pegawai Spesifik
        Schema::create('jam_kerja_assignment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pola_jam_kerja_id')->constrained('pola_jam_kerja')->cascadeOnDelete();
            $table->foreignId('skpd_id')->nullable()->constrained('skpd')->cascadeOnDelete();
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerja')->cascadeOnDelete();
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawai')->cascadeOnDelete();
            $table->date('berlaku_mulai')->nullable();
            $table->date('berlaku_sampai')->nullable();
            $table->timestamps();
            $table->index(['skpd_id', 'pola_jam_kerja_id']);
            $table->index(['pegawai_id', 'pola_jam_kerja_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jam_kerja_assignment');
        Schema::dropIfExists('jam_kerja_detail');
        Schema::dropIfExists('pola_jam_kerja');
    }
};
