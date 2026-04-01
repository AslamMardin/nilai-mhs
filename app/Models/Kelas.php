<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'kampus_id',
        'nama',
        'kode',
        'semester',
        'tahun_ajaran',
        'wali_kelas',
    ];

    // Relasi: banyak kelas → 1 kampus
    public function kampus(): BelongsTo
    {
        return $this->belongsTo(Kampus::class, 'kampus_id');
    }

    // Relasi: 1 kelas → banyak mahasiswa
    public function mahasiswa(): HasMany
    {
        return $this->hasMany(Mahasiswa::class, 'kelas_id');
    }

    // Relasi: 1 kelas → banyak mata kuliah
    public function mataKuliah(): HasMany
    {
        return $this->hasMany(MataKuliah::class, 'kelas_id');
    }

    // Accessor: nama lengkap kelas
    public function getNamaLengkapAttribute(): string
    {
        return "{$this->nama} - {$this->kampus->kode} ({$this->tahun_ajaran}/{$this->semester})";
    }
}
