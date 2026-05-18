<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nilai_keaktifan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->tinyInteger('pertemuan_ke');
            $table->decimal('skor', 5, 2)->default(0);
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'mata_kuliah_id', 'pertemuan_ke'], 'uq_keaktifan_detail');
        });

        Schema::create('nilai_tugas_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->string('nama_tugas', 150);
            $table->decimal('skor', 5, 2)->default(0);
            $table->timestamps();

            // Depending on requirements, maybe a user can have multiple identical task names, but usually unique per student-course-task.
            // Let's add a unique constraint just in case, but actually, they might make a typo or reuse names. 
            // We'll leave it without unique constraint on nama_tugas to allow flexibility, or we can enforce it.
            // Let's enforce it so we don't have duplicate records for the same task.
            $table->unique(['mahasiswa_id', 'mata_kuliah_id', 'nama_tugas'], 'uq_tugas_detail');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_tugas_detail');
        Schema::dropIfExists('nilai_keaktifan_detail');
    }
};
