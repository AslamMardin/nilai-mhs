<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\NilaiController;
use Illuminate\Support\Facades\Route;

// ── Dashboard ─────────────────────────────────────────────────────────────────
Route::get('/', fn () => view('dashboard'))->name('dashboard');

// ── Mahasiswa ─────────────────────────────────────────────────────────────────
Route::resource('mahasiswa', MahasiswaController::class);

Route::prefix('mahasiswa/{mahasiswa}')->name('mahasiswa.')->group(function () {
    Route::get ('enrollment',        [MahasiswaController::class, 'enrollment'])
         ->name('enrollment');
    Route::post('enrollment',        [MahasiswaController::class, 'enrollmentStore'])
         ->name('enrollment.store');
});

// ── Absensi ───────────────────────────────────────────────────────────────────
Route::prefix('absensi/{kelas}/{mataKuliah}')->name('absensi.')->group(function () {
    Route::get ('pertemuan/{pertemuan}', [AbsensiController::class, 'index'])
         ->name('index');
    Route::post('pertemuan/{pertemuan}', [AbsensiController::class, 'store'])
         ->name('store');
    Route::get ('rekap',                 [AbsensiController::class, 'rekap'])
         ->name('rekap');
});

// ── Nilai ─────────────────────────────────────────────────────────────────────
Route::prefix('nilai/{kelas}/{mataKuliah}')->name('nilai.')->group(function () {
    Route::get ('',           [NilaiController::class, 'index'])
         ->name('index');
    Route::post('',           [NilaiController::class, 'store'])
         ->name('store');
    Route::post('finalisasi', [NilaiController::class, 'finalisasi'])
         ->name('finalisasi');
});
Route::get('nilai/detail/{enrollment}', [NilaiController::class, 'show'])
     ->name('nilai.show');

// ── Laporan ───────────────────────────────────────────────────────────────────
Route::prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/',                                    [LaporanController::class, 'index'])
         ->name('index');
    Route::get('kelas/{kelas}/{mataKuliah}',           [LaporanController::class, 'rekapKelas'])
         ->name('rekap-kelas');
    Route::get('kelas/{kelas}/{mataKuliah}/pdf',       [LaporanController::class, 'rekapKelasPdf'])
         ->name('rekap-kelas-pdf');
    Route::get('kehadiran/{kelas}/{mataKuliah}',       [LaporanController::class, 'kehadiran'])
         ->name('kehadiran');
    Route::get('institusi/{institusi}',                [LaporanController::class, 'institusi'])
         ->name('institusi');
    Route::get('transkrip/{mahasiswa}',                [LaporanController::class, 'transkrip'])
         ->name('transkrip');
});
