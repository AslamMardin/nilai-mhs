<?php

// ==========================================
// Model: NilaiAkhir
// ==========================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiAkhir extends Model
{
    use HasFactory;

    protected $table = 'nilai_akhir';

    protected $fillable = [
        'mahasiswa_id',
        'mata_kuliah_id',
        'nilai_teori',
        'nilai_praktikum',
        'nilai_akhir',
        'huruf_mutu',
        'persentase_kehadiran',
        'poin_kehadiran',
        'status_kelulusan',
        'keterangan_gagal',
    ];

    protected $casts = [
        'nilai_teori'          => 'float',
        'nilai_praktikum'      => 'float',
        'nilai_akhir'          => 'float',
        'persentase_kehadiran' => 'float',
        'poin_kehadiran'       => 'integer',
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
     * Konversi nilai angka ke huruf mutu
     * >= 85  : A
     * 75-84  : B
     * 65-74  : C
     * 55-64  : D
     * < 55   : E (Tidak Lulus)
     */
    public static function konversiHurufMutu(float $nilai): string
    {
        return match(true) {
            $nilai >= 85 => 'A',
            $nilai >= 75 => 'B',
            $nilai >= 65 => 'C',
            $nilai >= 55 => 'D',
            default      => 'E',
        };
    }

    // Accessor: warna badge huruf mutu
    public function getWarnaHurufMutuAttribute(): string
    {
        return match($this->huruf_mutu) {
            'A' => 'success',
            'B' => 'primary',
            'C' => 'warning',
            'D' => 'orange',
            'E' => 'danger',
            default => 'secondary',
        };
    }
}
