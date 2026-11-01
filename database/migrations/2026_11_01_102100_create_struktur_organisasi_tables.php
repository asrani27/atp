<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skpd', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique();
            $table->string('nama');
            $table->string('singkatan', 50)->nullable();
            $table->text('alamat')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->unsignedInteger('radius_meter')->default(100);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('unit_kerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skpd_id')->constrained('skpd')->cascadeOnDelete();
            $table->string('nama');
            $table->enum('jenis', ['PUSKESMAS', 'RSUD', 'LABKES', 'IFK', 'SEKOLAH', 'UPTD', 'BAGIAN'])->default('UPTD');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->unsignedInteger('radius_meter')->default(100);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
            $table->index(['skpd_id', 'jenis']);
        });

        Schema::create('pangkat', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('golongan', 10);
            $table->timestamps();
        });

        Schema::create('eselon', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->timestamps();
        });

        Schema::create('jabatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skpd_id')->constrained('skpd')->cascadeOnDelete();
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerja')->nullOnDelete();
            $table->string('nama');
            $table->enum('jenis', ['STRUKTURAL', 'JFT', 'JFU', 'GURU', 'NAKES'])->default('JFU');
            $table->unsignedTinyInteger('kelas')->default(1);
            $table->decimal('pagu_beban_kerja', 15, 2)->default(0);
            $table->decimal('pagu_prestasi_kerja', 15, 2)->default(0);
            $table->decimal('pagu_kondisi_kerja', 15, 2)->default(0);
            $table->decimal('pagu_kelangkaan_profesi', 15, 2)->default(0);
            $table->decimal('pagu_total', 15, 2)->default(0);
            $table->foreignId('atasan_id')->nullable()->constrained('jabatan')->nullOnDelete();
            $table->boolean('is_sekda')->default(false);
            $table->boolean('is_kadis')->default(false);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
            $table->index(['skpd_id', 'kelas']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jabatan');
        Schema::dropIfExists('eselon');
        Schema::dropIfExists('pangkat');
        Schema::dropIfExists('unit_kerja');
        Schema::dropIfExists('skpd');
    }
};
