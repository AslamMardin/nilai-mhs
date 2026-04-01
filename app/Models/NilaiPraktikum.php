<?php
// ==========================================
// Model: NilaiPraktikum
// ==========================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiPraktikum extends Model
{
    use HasFactory;

    protected $table = 'nilai_praktikum';

    protected $fillable = [
        'mahasiswa_id',
        'mata_kuliah_id',
        'nilai_praktikum',
    ];

    protected $casts = [
        'nilai_praktikum' => 'float',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    /**
     * Nilai praktikum = 100% dari nilai_praktikum
     * Tidak ada pembobotan tambahan
     */
    public function getNilaiAkhirPraktikumAttribute(): float
    {
        return $this->nilai_praktikum;
    }
}
