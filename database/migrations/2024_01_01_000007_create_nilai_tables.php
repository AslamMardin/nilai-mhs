<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_teori', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            // Komponen nilai teori (0 - 100)
            $table->decimal('keaktifan', 5, 2)->default(0)->comment('Bobot 20%');
            $table->decimal('tugas', 5, 2)->default(0)->comment('Bobot 20%');
            $table->decimal('uts', 5, 2)->default(0)->comment('Bobot 25%');
            $table->decimal('uas', 5, 2)->default(0)->comment('Bobot 35%');
            // Nilai akhir teori (calculated)
            $table->decimal('nilai_akhir_teori', 5, 2)->default(0);
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'mata_kuliah_id'], 'uq_nilai_teori');
        });

        Schema::create('nilai_praktikum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            // Nilai praktikum (0 - 100), bobot 100%
            $table->decimal('nilai_praktikum', 5, 2)->default(0);
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'mata_kuliah_id'], 'uq_nilai_praktikum');
        });

        Schema::create('nilai_akhir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->decimal('nilai_teori', 5, 2)->default(0);
            $table->decimal('nilai_praktikum', 5, 2)->default(0);
            // Kombinasi 50:50 teori:praktikum
            $table->decimal('nilai_akhir', 5, 2)->default(0);
            // Huruf mutu berdasarkan nilai akhir
            $table->char('huruf_mutu', 2)->nullable();
            // Status kehadiran
            $table->decimal('persentase_kehadiran', 5, 2)->default(0);
            $table->integer('poin_kehadiran')->default(0);
            // Status lulus/tidak lulus
            $table->enum('status_kelulusan', ['lulus', 'tidak_lulus', 'belum_dinilai'])->default('belum_dinilai');
            $table->string('keterangan_gagal')->nullable()->comment('Alasan gagal jika tidak lulus');
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'mata_kuliah_id'], 'uq_nilai_akhir');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_akhir');
        Schema::dropIfExists('nilai_praktikum');
        Schema::dropIfExists('nilai_teori');
    }
};
