<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kampus_id')->constrained('kampus')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->string('kode', 20)->unique();
            $table->string('nama', 150);
            $table->tinyInteger('sks')->default(2);
            $table->enum('jenis', ['teori', 'praktikum', 'teori_praktikum'])->default('teori');
            $table->string('dosen', 100)->nullable();
            $table->tinyInteger('total_pertemuan')->default(16);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('mata_kuliah'); }
};
