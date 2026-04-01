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
