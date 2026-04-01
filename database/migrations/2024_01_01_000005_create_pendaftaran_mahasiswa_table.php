<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->year('tahun_ajaran');
            $table->enum('semester', ['ganjil', 'genap']);
            $table->enum('status', ['aktif', 'mengulang', 'lulus', 'tidak_lulus'])->default('aktif');
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'mata_kuliah_id', 'tahun_ajaran', 'semester'], 'uq_pendaftaran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_mahasiswa');
    }
};
