<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kampus_id')->constrained('kampus')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->string('nim', 20)->unique();
            $table->string('nama', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('email', 100)->nullable()->unique();
            $table->string('telepon', 20)->nullable();
            $table->string('alamat')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('tempat_lahir', 50)->nullable();
            $table->enum('status', ['aktif', 'cuti', 'lulus', 'dropout'])->default('aktif');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('mahasiswa'); }
};
