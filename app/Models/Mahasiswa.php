<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'kampus_id',
        'kelas_id',
        'nim',
        'nama',
        'jenis_kelamin',
        'email',
        'telepon',
        'alamat',
        'tanggal_lahir',
        'tempat_lahir',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // Relasi: banyak mahasiswa → 1 kampus
    public function kampus(): BelongsTo
    {
        return $this->belongsTo(Kampus::class, 'kampus_id');
    }

    // Relasi: banyak mahasiswa → 1 kelas
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Relasi: banyak-ke-banyak dengan mata kuliah (mahasiswa bisa daftar banyak matkul)
    public function mataKuliah(): BelongsToMany
    {
        return $this->belongsToMany(
            MataKuliah::class,
            'pendaftaran_mahasiswa',
            'mahasiswa_id',
            'mata_kuliah_id'
        )->withPivot(['tahun_ajaran', 'semester', 'status'])
         ->withTimestamps();
    }

    // Relasi: absensi milik mahasiswa ini
    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class, 'mahasiswa_id');
    }

    // Relasi: nilai teori
    public function nilaiTeori(): HasMany
    {
        return $this->hasMany(NilaiTeori::class, 'mahasiswa_id');
    }

    // Relasi: nilai praktikum
    public function nilaiPraktikum(): HasMany
    {
        return $this->hasMany(NilaiPraktikum::class, 'mahasiswa_id');
    }

    // Relasi: nilai akhir (semua mata kuliah)
    public function nilaiAkhir(): HasMany
    {
        return $this->hasMany(NilaiAkhir::class, 'mahasiswa_id');
    }

    // Accessor: Nama lengkap + NIM
    public function getNamaLengkapAttribute(): string
    {
        return "{$this->nim} - {$this->nama}";
    }

    // IPK rata-rata berdasarkan huruf mutu
    public function getIpkAttribute(): float
    {
        $nilaiAkhir = $this->nilaiAkhir()->pluck('nilai_akhir');

        if ($nilaiAkhir->isEmpty()) return 0.0;

        return round($nilaiAkhir->average(), 2);
    }
}
