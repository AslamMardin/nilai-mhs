<?php

// ==========================================
// Model: NilaiTeori
// ==========================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiTeori extends Model
{
    use HasFactory;

    protected $table = 'nilai_teori';

    protected $fillable = [
        'mahasiswa_id',
        'mata_kuliah_id',
        'keaktifan',
        'tugas',
        'uts',
        'uas',
        'nilai_akhir_teori',
    ];

    protected $casts = [
        'keaktifan'        => 'float',
        'tugas'            => 'float',
        'uts'              => 'float',
        'uas'              => 'float',
        'nilai_akhir_teori' => 'float',
    ];

    /**
     * Bobot komponen teori:
     * Keaktifan: 20%
     * Tugas    : 20%
     * UTS      : 25%
     * UAS      : 35%
     */
    public const BOBOT = [
        'keaktifan' => 0.20,
        'tugas'     => 0.20,
        'uts'       => 0.25,
        'uas'       => 0.35,
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
     * Hitung nilai akhir teori berdasarkan bobot
     * NA_Teori = (keaktifan*0.20) + (tugas*0.20) + (uts*0.25) + (uas*0.35)
     */
    public function hitungNilaiAkhirTeori(): float
    {
        return round(
            ($this->keaktifan * self::BOBOT['keaktifan']) +
            ($this->tugas     * self::BOBOT['tugas']) +
            ($this->uts       * self::BOBOT['uts']) +
            ($this->uas       * self::BOBOT['uas']),
            2
        );
    }

    /**
     * Simpan dan perbarui nilai akhir teori secara otomatis
     */
    public static function simpanDanHitung(array $data): self
    {
        $instance = self::updateOrCreate(
            [
                'mahasiswa_id'  => $data['mahasiswa_id'],
                'mata_kuliah_id' => $data['mata_kuliah_id'],
            ],
            [
                'keaktifan' => $data['keaktifan'] ?? 0,
                'tugas'     => $data['tugas'] ?? 0,
                'uts'       => $data['uts'] ?? 0,
                'uas'       => $data['uas'] ?? 0,
            ]
        );

        $instance->nilai_akhir_teori = $instance->hitungNilaiAkhirTeori();
        $instance->save();

        return $instance;
    }
}

