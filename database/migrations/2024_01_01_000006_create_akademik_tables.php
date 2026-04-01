<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // ── Pivot: mahasiswa ↔ mata_kuliah ──────────────────────
        Schema::create('pendaftaran_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->year('tahun_ajaran');
            $table->enum('semester', ['ganjil', 'genap']);
            $table->enum('status', ['aktif', 'mengulang', 'lulus', 'tidak_lulus'])->default('aktif');
            $table->timestamps();
            $table->unique(['mahasiswa_id', 'mata_kuliah_id'], 'uq_pendaftaran');
        });

        // ── Absensi ─────────────────────────────────────────────
        // H=2, T=1, S=1, I=0, A=0 | syarat lulus ≥ 75%
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->tinyInteger('pertemuan_ke');
            $table->date('tanggal');
            $table->enum('status', ['H', 'T', 'S', 'I', 'A'])->default('H');
            $table->string('keterangan', 200)->nullable();
            $table->timestamps();
            $table->unique(['mahasiswa_id', 'mata_kuliah_id', 'pertemuan_ke'], 'uq_absensi');
        });

        // ── Nilai Teori (bobot: aktif20, tugas20, uts25, uas35) ─
        Schema::create('nilai_teori', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->decimal('keaktifan', 5, 2)->default(0);
            $table->decimal('tugas', 5, 2)->default(0);
            $table->decimal('uts', 5, 2)->default(0);
            $table->decimal('uas', 5, 2)->default(0);
            $table->decimal('nilai_akhir_teori', 5, 2)->default(0);
            $table->timestamps();
            $table->unique(['mahasiswa_id', 'mata_kuliah_id'], 'uq_nilai_teori');
        });

        // ── Nilai Praktikum (bobot: 100%) ───────────────────────
        Schema::create('nilai_praktikum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->decimal('nilai_praktikum', 5, 2)->default(0);
            $table->timestamps();
            $table->unique(['mahasiswa_id', 'mata_kuliah_id'], 'uq_nilai_praktikum');
        });

        // ── Nilai Akhir (hasil kalkulasi final) ─────────────────
        Schema::create('nilai_akhir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->decimal('nilai_teori', 5, 2)->default(0);
            $table->decimal('nilai_praktikum', 5, 2)->default(0);
            $table->decimal('nilai_akhir', 5, 2)->default(0);
            $table->char('huruf_mutu', 1)->nullable();
            $table->decimal('persentase_kehadiran', 5, 2)->default(0);
            $table->smallInteger('poin_kehadiran')->default(0);
            $table->enum('status_kelulusan', ['lulus', 'tidak_lulus', 'belum_dinilai'])->default('belum_dinilai');
            $table->string('keterangan_gagal', 200)->nullable();
            $table->timestamps();
            $table->unique(['mahasiswa_id', 'mata_kuliah_id'], 'uq_nilai_akhir');
        });
    }

    public function down(): void {
        Schema::dropIfExists('nilai_akhir');
        Schema::dropIfExists('nilai_praktikum');
        Schema::dropIfExists('nilai_teori');
        Schema::dropIfExists('absensi');
        Schema::dropIfExists('pendaftaran_mahasiswa');
    }
};
