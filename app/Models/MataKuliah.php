<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah';

    protected $fillable = [
        'kampus_id',
        'kelas_id',
        'kode',
        'nama',
        'sks',
        'jenis',
        'dosen',
        'total_pertemuan',
    ];

    // Relasi: banyak mata kuliah → 1 kampus
    public function kampus(): BelongsTo
    {
        return $this->belongsTo(Kampus::class, 'kampus_id');
    }

    // Relasi: banyak mata kuliah → 1 kelas
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Relasi: banyak-ke-banyak dengan mahasiswa via pendaftaran_mahasiswa
    public function mahasiswa(): BelongsToMany
    {
        return $this->belongsToMany(
            Mahasiswa::class,
            'pendaftaran_mahasiswa',
            'mata_kuliah_id',
            'mahasiswa_id'
        )->withPivot(['tahun_ajaran', 'semester', 'status'])
         ->withTimestamps();
    }

    // Relasi: absensi
    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class, 'mata_kuliah_id');
    }

    // Relasi: nilai teori
    public function nilaiTeori(): HasMany
    {
        return $this->hasMany(NilaiTeori::class, 'mata_kuliah_id');
    }

    // Relasi: nilai praktikum
    public function nilaiPraktikum(): HasMany
    {
        return $this->hasMany(NilaiPraktikum::class, 'mata_kuliah_id');
    }

    // Relasi: nilai akhir
    public function nilaiAkhir(): HasMany
    {
        return $this->hasMany(NilaiAkhir::class, 'mata_kuliah_id');
    }

    // Cek apakah mata kuliah memiliki komponen praktikum
    public function hasPraktikum(): bool
    {
        return in_array($this->jenis, ['praktikum', 'teori_praktikum']);
    }

    // Cek apakah mata kuliah memiliki komponen teori
    public function hasTeori(): bool
    {
        return in_array($this->jenis, ['teori', 'teori_praktikum']);
    }
}
