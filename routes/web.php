<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController, DashboardController,
    KampusController, KelasController, MataKuliahController,
    MahasiswaController, AbsensiController, NilaiController, LaporanController
};

// ── Auth (guest) ─────────────────────────────────────────────
Route::middleware('guest')->group(function() {
    Route::redirect('/', '/login', 301);;
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ── Auth (authenticated) ─────────────────────────────────────
Route::middleware('auth')->group(function() {
    Route::post('/logout',          [AuthController::class, 'logout'])->name('logout');
    Route::get('/pilih-kampus',     [AuthController::class, 'showPilihKampus'])->name('pilih-kampus');
    Route::post('/pilih-kampus',    [AuthController::class, 'simpanPilihKampus'])->name('simpan-kampus');
    Route::post('/ganti-kampus',    [AuthController::class, 'gantiKampus'])->name('ganti-kampus');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master data
    Route::resource('kampus',      KampusController::class)->except(['show']);
    Route::resource('kelas',       KelasController::class);
    Route::resource('matakuliah',  MataKuliahController::class)->except(['show']);
    Route::resource('mahasiswa',   MahasiswaController::class);
    Route::get('/mahasiswa/{mahasiswa}/daftar-matkul',  [MahasiswaController::class, 'formDaftar'])->name('mahasiswa.form-daftar');
    Route::post('/mahasiswa/{mahasiswa}/daftar-matkul', [MahasiswaController::class, 'simpanDaftar'])->name('mahasiswa.simpan-daftar');

    // Absensi
    Route::get('/absensi',                    [AbsensiController::class, 'pilih'])->name('absensi.pilih');
    Route::get('/absensi/{mataKuliah}',       [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi/{mataKuliah}',      [AbsensiController::class, 'simpan'])->name('absensi.simpan');
    Route::get('/absensi/{mataKuliah}/rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');

    // Nilai
    Route::get('/nilai',                              [NilaiController::class, 'pilih'])->name('nilai.pilih');
    Route::get('/nilai/{mataKuliah}',                 [NilaiController::class, 'index'])->name('nilai.index');
    Route::get('/nilai/{mataKuliah}/teori',           [NilaiController::class, 'formTeori'])->name('nilai.form-teori');
    Route::post('/nilai/{mataKuliah}/teori',          [NilaiController::class, 'simpanTeori'])->name('nilai.simpan-teori');
    Route::get('/nilai/{mataKuliah}/praktikum',       [NilaiController::class, 'formPraktikum'])->name('nilai.form-praktikum');
    Route::post('/nilai/{mataKuliah}/praktikum',      [NilaiController::class, 'simpanPraktikum'])->name('nilai.simpan-praktikum');

    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function() {
        Route::get('/nilai-kelas',       [LaporanController::class, 'nilaiKelas'])->name('nilai-kelas');
        Route::get('/rekap',             [LaporanController::class, 'rekap'])->name('rekap');
        Route::get('/transkrip',         [LaporanController::class, 'transkrip'])->name('transkrip');
        Route::get('/rekap-mk/{mataKuliah}', [LaporanController::class, 'rekapMk'])->name('rekap-mk');
    });
});
