<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KampusController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\NilaiController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────
// GUEST ROUTES (Belum Login)
// ─────────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::get('/login', [LoginController::class, 'create']);
    Route::post('/login', [LoginController::class, 'store'])->name('login-post');
});

// ─────────────────────────────────────────────────────────────────
// AUTHENTICATED ROUTES (Sudah Login)
// ─────────────────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {

    // Logout
   Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Kampus ──────────────────────────────────────────────
    Route::resource('kampus', KampusController::class);

    // ── Kelas ───────────────────────────────────────────────
    Route::resource('kelas', KelasController::class);

    // ── Mata Kuliah ─────────────────────────────────────────
    Route::resource('mata-kuliah', MataKuliahController::class)->names([
        'index'   => 'mata-kuliah.index',
        'create'  => 'mata-kuliah.create',
        'store'   => 'mata-kuliah.store',
        'show'    => 'mata-kuliah.show',
        'edit'    => 'mata-kuliah.edit',
        'update'  => 'mata-kuliah.update',
        'destroy' => 'mata-kuliah.destroy',
    ]);

    // ── Mahasiswa ───────────────────────────────────────────
    Route::resource('mahasiswa', MahasiswaController::class);

    // Pendaftaran mahasiswa ke mata kuliah
    Route::get('/mahasiswa/{mahasiswa}/daftar-matkul',
        [MahasiswaController::class, 'formDaftar'])->name('mahasiswa.form-daftar');
    Route::post('/mahasiswa/{mahasiswa}/daftar-matkul',
        [MahasiswaController::class, 'simpanDaftar'])->name('mahasiswa.simpan-daftar');

    // ── Absensi ─────────────────────────────────────────────
    // Daftar & form input absensi per mata kuliah
    Route::get('/absensi/{mataKuliah}',
        [AbsensiController::class, 'index'])->name('absensi.index');

    // Simpan absensi (bulk per pertemuan)
    Route::post('/absensi/{mataKuliah}',
        [AbsensiController::class, 'simpan'])->name('absensi.simpan');

    // Rekap kehadiran per mata kuliah
    Route::get('/absensi/{mataKuliah}/rekap',
        [AbsensiController::class, 'rekap'])->name('absensi.rekap');

    // ── Nilai ───────────────────────────────────────────────
    // Daftar nilai semua mahasiswa per mata kuliah
    Route::get('/nilai/{mataKuliah}',
        [NilaiController::class, 'index'])->name('nilai.index');

    // Form input & simpan nilai teori
    Route::get('/nilai/{mataKuliah}/teori',
        [NilaiController::class, 'formTeori'])->name('nilai.form-teori');
    Route::post('/nilai/{mataKuliah}/teori',
        [NilaiController::class, 'simpanTeori'])->name('nilai.simpan-teori');

    // Form input & simpan nilai praktikum
    Route::get('/nilai/{mataKuliah}/praktikum',
        [NilaiController::class, 'formPraktikum'])->name('nilai.form-praktikum');
    Route::post('/nilai/{mataKuliah}/praktikum',
        [NilaiController::class, 'simpanPraktikum'])->name('nilai.simpan-praktikum');

    // Hitung ulang nilai akhir (trigger manual)
    Route::post('/nilai/{mataKuliah}/hitung-akhir/{mahasiswa}',
        [NilaiController::class, 'hitungDanSimpanNilaiAkhir'])->name('nilai.hitung-akhir');

    // Rekap nilai per mata kuliah
    Route::get('/nilai/{mataKuliah}/rekap',
        [NilaiController::class, 'rekapNilai'])->name('nilai.rekap');

    // ── Laporan ─────────────────────────────────────────────
    Route::prefix('laporan')->name('laporan.')->group(function () {

        // Nilai per kelas
        Route::get('/nilai-per-kelas',
            [LaporanController::class, 'nilaiPerKelas'])->name('nilai-per-kelas');

        // Rekap statistik per kampus
        Route::get('/rekap-kampus',
            [LaporanController::class, 'rekapKampus'])->name('rekap-kampus');

        // Transkrip nilai seorang mahasiswa
        Route::get('/transkrip',
            [LaporanController::class, 'transkripMahasiswa'])->name('transkrip');

        // Export PDF
        Route::get('/export-pdf/{mataKuliah}',
            [LaporanController::class, 'exportPdf'])->name('export-pdf');
    });

});
