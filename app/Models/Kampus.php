<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kampus extends Model
{
    use HasFactory;

    protected $table = 'kampus';

    protected $fillable = [
        'nama',
        'kode',
        'alamat',
        'telepon',
    ];

    // Relasi: 1 kampus → banyak kelas
    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class, 'kampus_id');
    }

    // Relasi: 1 kampus → banyak mata kuliah
    public function mataKuliah(): HasMany
    {
        return $this->hasMany(MataKuliah::class, 'kampus_id');
    }

    // Relasi: 1 kampus → banyak mahasiswa
    public function mahasiswa(): HasMany
    {
        return $this->hasMany(Mahasiswa::class, 'kampus_id');
    }

    // Hitung total mahasiswa aktif
    public function getTotalMahasiswaAktifAttribute(): int
    {
        return $this->mahasiswa()->where('status', 'aktif')->count();
    }
}
