<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. SKP (Sasaran Kinerja Pegawai)
        Schema::create('skp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->year('tahun');
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->enum('pendekatan', ['KUANTITATIF', 'KUALITATIF'])->default('KUANTITATIF');
            $table->enum('status', ['DRAFT', 'DIAJUKAN', 'DISETUJUI', 'DITOLAK'])->default('DRAFT');
            $table->foreignId('pejabat_penilai_id')->nullable()->constrained('pegawai')->nullOnDelete();
            $table->timestamps();
            $table->index(['pegawai_id', 'tahun']);
        });

        // 2. RHK (Rencana Hasil Kerja)
        Schema::create('skp_rhk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skp_id')->constrained('skp')->cascadeOnDelete();
            $table->enum('jenis', ['UTAMA', 'TAMBAHAN'])->default('UTAMA');
            $table->text('rencana_kinerja');
            $table->text('aspek_kuantitas')->nullable();
            $table->text('aspek_kualitas')->nullable();
            $table->text('aspek_waktu')->nullable();
            $table->timestamps();
        });

        // 3. Aktivitas Harian TPP
        Schema::create('aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->foreignId('skp_rhk_id')->nullable()->constrained('skp_rhk')->nullOnDelete();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->unsignedSmallInteger('durasi_menit');
            $table->text('uraian');
            $table->string('file_bukti')->nullable();
            $table->enum('status_validasi', ['PENDING', 'DISETUJUI', 'DITOLAK'])->default('PENDING');
            $table->foreignId('validator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->text('catatan_validator')->nullable();
            $table->timestamps();

            $table->index(['pegawai_id', 'tanggal']);
            $table->index(['tanggal', 'status_validasi']);
        });

        // 4. Sanksi Disiplin Pegawai (Mempengaruhi Pemotongan TPP)
        Schema::create('sanksi_disiplin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->string('nomor_sk', 100)->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->enum('tingkat_hukuman', ['RINGAN', 'SEDANG', 'BERAT']);
            $table->string('jenis_hukuman'); // Teguran Lisan, Tertulis, Penundaan KGB, dll
            $table->date('mulai_berlaku');
            $table->date('selesai_berlaku');
            $table->decimal('persen_potongan_tpp', 5, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sanksi_disiplin');
        Schema::dropIfExists('aktivitas');
        Schema::dropIfExists('skp_rhk');
        Schema::dropIfExists('skp');
    }
};
