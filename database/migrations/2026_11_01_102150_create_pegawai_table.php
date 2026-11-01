<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nip', 18)->unique();
            $table->string('nama');
            $table->string('gelar_depan', 50)->nullable();
            $table->string('gelar_belakang', 50)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            
            // Relasi Organisasi
            $table->foreignId('skpd_id')->constrained('skpd')->restrictOnDelete();
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerja')->nullOnDelete();
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatan')->nullOnDelete();
            $table->foreignId('jabatan_plt_id')->nullable()->constrained('jabatan')->nullOnDelete();
            $table->foreignId('jabatan_plh_id')->nullable()->constrained('jabatan')->nullOnDelete();
            $table->foreignId('pangkat_id')->nullable()->constrained('pangkat')->nullOnDelete();
            $table->foreignId('eselon_id')->nullable()->constrained('eselon')->nullOnDelete();

            // Status & Identitas Kepegawaian
            $table->enum('status_kepegawaian', ['PNS', 'CPNS', 'PPPK', 'PPPK_PARUH_WAKTU'])->default('PNS');
            $table->string('npwp', 30)->nullable();
            $table->string('status_ptkp', 10)->default('TK/0')->comment('TK/0, TK/1, K/0, K/1, dll');
            $table->unsignedTinyInteger('jumlah_tanggungan')->default(0);
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->decimal('tunjangan_keluarga', 15, 2)->default(0);
            $table->decimal('tunjangan_jabatan', 15, 2)->default(0);
            $table->decimal('tunjangan_fungsional', 15, 2)->default(0);
            $table->string('nama_bank', 50)->default('BANK KALSEL');
            $table->string('no_rekening', 50)->nullable();
            $table->string('golongan_darah', 5)->nullable();
            $table->string('foto')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();

            $table->index(['skpd_id', 'is_aktif']);
            $table->index(['unit_kerja_id', 'is_aktif']);
            $table->index(['status_kepegawaian', 'is_aktif']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
