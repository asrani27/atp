<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Lokasi Presensi / Geofence Zones
        Schema::create('lokasi_presensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skpd_id')->nullable()->constrained('skpd')->cascadeOnDelete();
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerja')->cascadeOnDelete();
            $table->string('nama');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->unsignedInteger('radius_meter')->default(100);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });

        // 2. Hari Libur Nasional & Cuti Bersama
        Schema::create('libur_nasional', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->unique();
            $table->string('keterangan');
            $table->boolean('is_cuti_bersama')->default(false);
            $table->timestamps();
        });

        // 3. Pengajuan Cuti ASN
        Schema::create('cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->enum('jenis_cuti', ['TAHUNAN', 'BESAR', 'SAKIT', 'MELAHIRKAN', 'KARENA_ALASAN_PENTING', 'DILUAR_TANGGUNGAN_NEGARA'])->default('TAHUNAN');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->unsignedSmallInteger('jumlah_hari');
            $table->text('alasan');
            $table->string('lampiran')->nullable();
            $table->enum('status', ['MENUNGGU', 'DISETUJUI_ATASAN', 'DISETUJUI_BKPSDM', 'DITOLAK'])->default('MENUNGGU');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('disetujui_pada')->nullable();
            $table->text('catatan_penolakan')->nullable();
            $table->timestamps();
            $table->index(['pegawai_id', 'status']);
        });

        // 4. Log Presensi / Kehadiran Harian
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->date('tanggal');
            $table->dateTime('jam_masuk')->nullable();
            $table->dateTime('jam_pulang')->nullable();
            $table->foreignId('lokasi_masuk_id')->nullable()->constrained('lokasi_presensi')->nullOnDelete();
            $table->foreignId('lokasi_pulang_id')->nullable()->constrained('lokasi_presensi')->nullOnDelete();
            $table->decimal('lat_masuk', 10, 8)->nullable();
            $table->decimal('long_masuk', 11, 8)->nullable();
            $table->decimal('lat_pulang', 10, 8)->nullable();
            $table->decimal('long_pulang', 11, 8)->nullable();
            $table->decimal('jarak_masuk_meter', 10, 2)->nullable()->comment('Dihitung di backend via Haversine');
            $table->decimal('jarak_pulang_meter', 10, 2)->nullable();
            $table->string('foto_masuk')->nullable();
            $table->string('foto_pulang')->nullable();
            $table->enum('status_kehadiran', ['HADIR', 'TERLAMBAT', 'PULANG_CEPAT', 'IZIN', 'SAKIT', 'CUTI', 'DINAS_LUAR', 'ALPA'])->default('HADIR');
            $table->unsignedSmallInteger('menit_keterlambatan')->default(0);
            $table->unsignedSmallInteger('menit_pulang_cepat')->default(0);
            $table->boolean('is_apel')->default(false);
            $table->boolean('is_terkunci')->default(false);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['pegawai_id', 'tanggal']);
            $table->index(['tanggal', 'status_kehadiran']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi');
        Schema::dropIfExists('cuti');
        Schema::dropIfExists('libur_nasional');
        Schema::dropIfExists('lokasi_presensi');
    }
};
