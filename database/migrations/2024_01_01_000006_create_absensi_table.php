<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->integer('pertemuan_ke')->comment('1 - 16');
            $table->date('tanggal');
            // H=Hadir(2), T=Terlambat(1), S=Sakit(1), I=Izin(0), A=Absen(0)
            $table->enum('status', ['H', 'T', 'S', 'I', 'A'])->default('H');
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'mata_kuliah_id', 'pertemuan_ke'], 'uq_absensi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
