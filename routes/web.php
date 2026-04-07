<?php

use App\Http\Controllers\{AuthController, DashboardController, KampusController, KelasController, MataKuliahController, MahasiswaController, AbsensiController, NilaiController, LaporanController};
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BobotNilaiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ── Auth (guest) ─────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::redirect('/', '/login', 301);;
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ── Auth (authenticated) ─────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout',          [AuthController::class, 'logout'])->name('logout');
    Route::get('/pilih-kampus',     [AuthController::class, 'showPilihKampus'])->name('pilih-kampus');
    Route::post('/pilih-kampus',    [AuthController::class, 'simpanPilihKampus'])->name('simpan-kampus');
    Route::post('/ganti-kampus',    [AuthController::class, 'gantiKampus'])->name('ganti-kampus');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master data
    Route::resource('kampus',      KampusController::class)->except(['show'])->parameters(['kampus' => 'kampus']);
    Route::resource('kelas',       KelasController::class)->parameters(['kelas' => 'kelas']);


    // 🔥 route custom dulu
    Route::get('/matakuliah/{id}/peserta', [MatakuliahController::class, 'peserta'])
        ->name('matakuliah.peserta');

    Route::get('/matakuliah/{id}/daftar', [MatakuliahController::class, 'daftar'])
        ->name('matakuliah.daftar');

    Route::post('/matakuliah/{id}/daftar', [MatakuliahController::class, 'storeDaftar'])
        ->name('matakuliah.daftar.store');

    Route::delete('/matakuliah/remove-mahasiswa', [MatakuliahController::class, 'removeMahasiswa'])
        ->name('matakuliah.removeMahasiswa');

    // 🔥 baru resource
    Route::resource('matakuliah', MataKuliahController::class)
        ->except(['show']);

    //  mahasiswa

    // import dan export
    Route::get('/mahasiswa/import', [MahasiswaController::class, 'formImport'])
        ->name('mahasiswa.import');
    Route::post('/mahasiswa/import', [MahasiswaController::class, 'import'])
        ->name('mahasiswa.import.process');

    Route::get('/mahasiswa/template', [MahasiswaController::class, 'downloadTemplate'])
        ->name('mahasiswa.template');

    // hapus banyak data
    Route::delete('/mahasiswa/bulk-delete', [MahasiswaController::class, 'bulkDelete'])->name('mahasiswa.bulkDelete');





    Route::get('/mahasiswa/{mahasiswa}/daftar-matkul',  [MahasiswaController::class, 'formDaftar'])->name('mahasiswa.form-daftar');
    Route::post('/mahasiswa/{mahasiswa}/daftar-matkul', [MahasiswaController::class, 'simpanDaftar'])->name('mahasiswa.simpan-daftar');
    Route::resource('mahasiswa',   MahasiswaController::class)->parameters(['mahasiswa' => 'mahasiswa']);


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
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/nilai-kelas',       [LaporanController::class, 'nilaiKelas'])->name('nilai-kelas');
        Route::get('/rekap',             [LaporanController::class, 'rekap'])->name('rekap');
        Route::get('/transkrip',         [LaporanController::class, 'transkrip'])->name('transkrip');
        Route::get('/rekap-mk/{mataKuliah}', [LaporanController::class, 'rekapMk'])->name('rekap-mk');
        //    nilai
        Route::get(
            '/nilai-kelas/excel/{kelas}',
            [LaporanController::class, 'exportNilaiKelasExcel']
        )->name('nilai-kelas.excel');
        Route::get(
            '/nilai-kelas/pdf/{kelas}',
            [LaporanController::class, 'exportNilaiKelasPdf']
        )->name('nilai-kelas.pdf');
    });



    // pengaturan
    Route::prefix('backup')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('backup.index');
        Route::post('/run', [BackupController::class, 'run'])->name('backup.run');
        Route::get('/download/{file}', [BackupController::class, 'download'])->name('backup.download');
        Route::delete('/delete/{file}', [BackupController::class, 'delete'])->name('backup.delete');


        // bobot
        Route::get('/bobot-nilai', [BobotNilaiController::class, 'index'])->name('bobot.index');
Route::post('/bobot-nilai', [BobotNilaiController::class, 'update'])->name('bobot.update');
    

// PROFILE
Route::get('/profile', [ProfileController::class, 'edit'])
    ->name('profile.edit');

Route::put('/profile', [ProfileController::class, 'update'])
    ->name('profile.update');

// PASSWORD
Route::get('/ganti-password', [ProfileController::class, 'editPassword'])
    ->name('password.edit');

Route::put('/ganti-password', [ProfileController::class, 'updatePassword'])
    ->name('password.update');
    });
});
