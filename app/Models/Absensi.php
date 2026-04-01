<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'mahasiswa_id',
        'mata_kuliah_id',
        'pertemuan_ke',
        'tanggal',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Bobot kehadiran per status:
     * H (Hadir)     = 2
     * T (Terlambat) = 1
     * S (Sakit)     = 1
     * I (Izin)      = 0
     * A (Absen)     = 0
     */
    public const BOBOT_KEHADIRAN = [
        'H' => 2,
        'T' => 1,
        'S' => 1,
        'I' => 0,
        'A' => 0,
    ];

    public const LABEL_STATUS = [
        'H' => 'Hadir',
        'T' => 'Terlambat',
        'S' => 'Sakit',
        'I' => 'Izin',
        'A' => 'Absen',
    ];

    // Relasi
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    // Accessor: bobot poin untuk status ini
    public function getBobotAttribute(): int
    {
        return self::BOBOT_KEHADIRAN[$this->status] ?? 0;
    }

    // Accessor: label status kehadiran
    public function getLabelStatusAttribute(): string
    {
        return self::LABEL_STATUS[$this->status] ?? $this->status;
    }

    // Accessor: warna badge status
    public function getWarnaStatusAttribute(): string
    {
        return match($this->status) {
            'H' => 'success',
            'T' => 'warning',
            'S' => 'info',
            'I' => 'secondary',
            'A' => 'danger',
            default => 'light',
        };
    }

    /**
     * Hitung poin kehadiran mahasiswa untuk 1 mata kuliah
     * Rumus: jumlah bobot dari semua record absensi mahasiswa tsb
     */
    public static function hitungPoinKehadiran(int $mahasiswaId, int $mataKuliahId): int
    {
        $absensiList = self::where('mahasiswa_id', $mahasiswaId)
            ->where('mata_kuliah_id', $mataKuliahId)
            ->get();

        return $absensiList->sum(fn($a) => self::BOBOT_KEHADIRAN[$a->status] ?? 0);
    }

    /**
     * Hitung persentase kehadiran mahasiswa untuk 1 mata kuliah
     * Rumus: (poin_kehadiran / (total_pertemuan * 2)) * 100
     * Total pertemuan default 16, bobot tertinggi H=2
     *
     * Syarat lulus: persentase >= 75%
     */
    public static function hitungPersentaseKehadiran(int $mahasiswaId, int $mataKuliahId, int $totalPertemuan = 16): float
    {
        $poin = self::hitungPoinKehadiran($mahasiswaId, $mataKuliahId);
        $maxPoin = $totalPertemuan * 2; // Bobot maksimal jika semua H

        if ($maxPoin === 0) return 0.0;

        return round(($poin / $maxPoin) * 100, 2);
    }

    /**
     * Cek apakah mahasiswa memenuhi syarat kehadiran (>= 75%)
     */
    public static function lolosKehadiran(int $mahasiswaId, int $mataKuliahId, int $totalPertemuan = 16): bool
    {
        return self::hitungPersentaseKehadiran($mahasiswaId, $mataKuliahId, $totalPertemuan) >= 75.0;
    }
}
