<?php
// FILE: 2024_01_01_000003_create_kelas_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kampus_id')->constrained('kampus')->cascadeOnDelete();
            $table->string('nama', 80);
            $table->string('kode', 20)->unique();
            $table->enum('semester', ['ganjil', 'genap']);
            $table->year('tahun_ajaran');
            $table->string('wali_kelas', 100)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('kelas'); }
};
