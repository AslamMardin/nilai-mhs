<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\BobotNilai;
use App\Models\MataKuliah;
use App\Models\NilaiAkhir;
use App\Models\NilaiTeori;
use Illuminate\Support\Facades\DB;

class PenilaianService
{
    /**
     * Hitung Nilai Teori / NA Murni (Bobot 100%)
     * Rumus: (Kehadiran * 25%) + (Tugas * 20%) + (UTS * 25%) + (UAS * 30%)
     */
    public static function hitungNilaiTeori(float $kehadiran, float $tugas, float $uts, float $uas, ?BobotNilai $bobot = null): float
    {
        $b = $bobot ?? BobotNilai::first();
        $bKehadiran = ($b?->kehadiran ?? 25) / 100;
        $bTugas     = ($b?->tugas ?? 20) / 100;
        $bUts       = ($b?->uts ?? 25) / 100;
        $bUas       = ($b?->uas ?? 30) / 100;

        $nilai = ($kehadiran * $bKehadiran) + ($tugas * $bTugas) + ($uts * $bUts) + ($uas * $bUas);

        return round($nilai, 2);
    }

    /**
     * Hitung Bonus Keaktifan (Maksimal 10%)
     * Rumus: Keaktifan * 10%
     */
    public static function hitungBonusKeaktifan(float $keaktifan): float
    {
        return round($keaktifan * 0.10, 2);
    }

    /**
     * Hitung Nilai Finish
     * Rumus: MIN(100, NA Murni + Bonus Keaktifan)
     */
    public static function hitungNilaiFinish(float $naMurni, float $bonusKeaktifan): float
    {
        return min(100.0, round($naMurni + $bonusKeaktifan, 2));
    }

    /**
     * Hitung Nilai Akademik
     * Menggunakan bobot 100%: Kehadiran 25% + Tugas 20% + UTS 25% + UAS 30%
     */
    public static function hitungNilaiAkademik(mixed $kehadiranOrJenis, ...$args): float
    {
        if (is_string($kehadiranOrJenis)) {
            $naTeori = isset($args[0]) ? (float)$args[0] : 0.0;
            return round($naTeori, 2);
        }

        $kehadiran = (float)$kehadiranOrJenis;
        $tugas     = isset($args[0]) ? (float)$args[0] : 0.0;
        $uts       = isset($args[1]) ? (float)$args[1] : 0.0;
        $uas       = isset($args[2]) ? (float)$args[2] : 0.0;
        $bobot     = isset($args[3]) && $args[3] instanceof BobotNilai ? $args[3] : null;

        return self::hitungNilaiTeori($kehadiran, $tugas, $uts, $uas, $bobot);
    }

    /**
     * Batas Nilai Minimum berdasarkan persentase kehadiran:
     * >= 95%      -> 85
     * 90 - 94.99% -> 75
     * 80 - 89.99% -> 65
     * 75 - 79.99% -> 55
     * < 60%       -> null (Tidak Lulus / tidak ada minimum)
     */
    public static function hitungMinimumKehadiran(float $persenHadir): ?float
    {
        return match (true) {
            $persenHadir >= 95.0 => 85.0,
            $persenHadir >= 90.0 => 75.0,
            $persenHadir >= 80.0 => 65.0,
            $persenHadir >= 75.0 => 55.0,
            default              => null,
        };
    }

    /**
     * Hitung Nilai Akhir Final:
     * NA dan Mutu menggunakan Nilai Finish secara langsung (dibatasi 0 <= Nilai Akhir <= 100).
     * Tidak ada nilai alternatif atau tersembunyi.
     */
    public static function hitungNilaiAkhir(float $nilaiAkademik, float $persenHadir): float
    {
        $nilaiAkhir = $nilaiAkademik;

        if ($nilaiAkhir > 100.0) {
            $nilaiAkhir = 100.0;
        } elseif ($nilaiAkhir < 0.0) {
            $nilaiAkhir = 0.0;
        }

        return round($nilaiAkhir, 2);
    }

    /**
     * Konversi Nilai Akhir ke Huruf Mutu:
     * >= 85      -> A
     * 75 - 84.99 -> B
     * 65 - 74.99 -> C
     * 55 - 64.99 -> D
     * < 55       -> E
     */
    public static function konversiHurufMutu(float $nilaiAkhir): string
    {
        return match (true) {
            $nilaiAkhir >= 85.0 => 'A',
            $nilaiAkhir >= 75.0 => 'B',
            $nilaiAkhir >= 65.0 => 'C',
            $nilaiAkhir >= 55.0 => 'D',
            default             => 'E',
        };
    }

    /**
     * Syarat Kelulusan:
     * LULUS hanya jika: Kehadiran >= 60% DAN Nilai Akhir >= 55
     */
    public static function tentukanStatusKelulusan(float $persenHadir, float $nilaiAkhir): string
    {
        return ($persenHadir >= 60.0 && $nilaiAkhir >= 55.0) ? 'lulus' : 'tidak_lulus';
    }

    /**
     * Alasan Ketidaklulusan:
     * - Kehadiran < 60%: "Kehadiran 59% (minimal 60%)"
     * - Nilai akhir < 55: "Nilai akhir 50 (minimal 55)"
     * - Keduanya: "Kehadiran 59% (minimal 60%); Nilai akhir 50 (minimal 55)"
     * - Lulus: null
     */
    public static function tentukanKeteranganGagal(float $persenHadir, float $nilaiAkhir): ?string
    {
        $gagalKehadiran = $persenHadir < 60.0;
        $gagalNilai     = $nilaiAkhir < 55.0;

        if (!$gagalKehadiran && !$gagalNilai) {
            return null;
        }

        $fmtHadir = (float) $persenHadir;
        $fmtNilai = (float) $nilaiAkhir;

        if ($gagalKehadiran && $gagalNilai) {
            return "Kehadiran {$fmtHadir}% (minimal 60%); Nilai akhir {$fmtNilai} (minimal 55)";
        }

        if ($gagalKehadiran) {
            return "Kehadiran {$fmtHadir}% (minimal 60%)";
        }

        return "Nilai akhir {$fmtNilai} (minimal 55)";
    }

    /**
     * Proses kalkulasi & penyimpanan nilai lengkap untuk seorang mahasiswa pada sebuah mata kuliah.
     */
    public static function prosesNilaiMahasiswa(int $mahasiswaId, int $mataKuliahId): NilaiAkhir
    {
        $mk = MataKuliah::findOrFail($mataKuliahId);
        $totalPertemuan = $mk->total_pertemuan > 0 ? $mk->total_pertemuan : 16;

        $poinHadir   = Absensi::hitungPoin($mahasiswaId, $mataKuliahId);
        $persenHadir = Absensi::hitungPersen($mahasiswaId, $mataKuliahId, $totalPertemuan);

        $nt = NilaiTeori::where('mahasiswa_id', $mahasiswaId)->where('mata_kuliah_id', $mataKuliahId)->first();

        $tugas     = $nt ? (float)$nt->tugas : 0.0;
        $uts       = $nt ? (float)$nt->uts : 0.0;
        $uas       = $nt ? (float)$nt->uas : 0.0;
        $keaktifan = $nt ? (float)$nt->keaktifan : 0.0;

        // 1. NA Murni = (Hadir * 25%) + (Tugas * 20%) + (UTS * 25%) + (UAS * 30%)
        $naMurni = self::hitungNilaiTeori($persenHadir, $tugas, $uts, $uas);

        // 2. Bonus Keaktifan = Keaktifan * 10%
        $bonusKeaktifan = self::hitungBonusKeaktifan($keaktifan);

        // 3. Nilai Finish = MIN(100, NA Murni + Bonus Keaktifan)
        $nilaiFinish = self::hitungNilaiFinish($naMurni, $bonusKeaktifan);

        if ($nt && $nt->nilai_akhir_teori != $nilaiFinish) {
            $nt->nilai_akhir_teori = $nilaiFinish;
            $nt->save();
        }

        // 4. Nilai Akhir & Mutu menggunakan Nilai Finish
        $nilaiAkhirFinal = self::hitungNilaiAkhir($nilaiFinish, $persenHadir);
        $hurufMutu       = self::konversiHurufMutu($nilaiAkhirFinal);
        $status          = self::tentukanStatusKelulusan($persenHadir, $nilaiAkhirFinal);
        $keteranganGagal = self::tentukanKeteranganGagal($persenHadir, $nilaiAkhirFinal);

        $record = NilaiAkhir::updateOrCreate(
            ['mahasiswa_id' => $mahasiswaId, 'mata_kuliah_id' => $mataKuliahId],
            [
                'nilai_teori'          => $naMurni,
                'nilai_akhir'          => $nilaiAkhirFinal,
                'huruf_mutu'           => $hurufMutu,
                'persentase_kehadiran' => $persenHadir,
                'poin_kehadiran'       => $poinHadir,
                'status_kelulusan'     => $status,
                'keterangan_gagal'     => $keteranganGagal,
            ]
        );

        DB::table('pendaftaran_mahasiswa')
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('mata_kuliah_id', $mataKuliahId)
            ->update(['status' => $status === 'lulus' ? 'lulus' : 'tidak_lulus']);

        return $record;
    }

    /**
     * Sinkronisasi nilai setelah ada perubahan absensi untuk daftar mahasiswa tertentu.
     */
    public static function sinkronNilaiSetelahAbsensi(int $mataKuliahId, array $mahasiswaIds): void
    {
        $mk = MataKuliah::find($mataKuliahId);
        if (!$mk) {
            return;
        }

        foreach ($mahasiswaIds as $mahasiswaId) {
            // Hanya proses jika mahasiswa sudah memiliki data nilai
            $hasNilai = NilaiTeori::where('mahasiswa_id', $mahasiswaId)->where('mata_kuliah_id', $mataKuliahId)->exists()
                || NilaiAkhir::where('mahasiswa_id', $mahasiswaId)->where('mata_kuliah_id', $mataKuliahId)->exists();

            if ($hasNilai) {
                self::prosesNilaiMahasiswa($mahasiswaId, $mataKuliahId);
            }
        }
    }

    /**
     * Kalkulasi ulang seluruh nilai mahasiswa pada satu mata kuliah.
     */
    public static function kalkulasiUlangMataKuliah(int $mataKuliahId): void
    {
        $mk = MataKuliah::with('mahasiswa')->findOrFail($mataKuliahId);
        foreach ($mk->mahasiswa as $mhs) {
            $hasNilai = NilaiTeori::where('mahasiswa_id', $mhs->id)->where('mata_kuliah_id', $mataKuliahId)->exists()
                || NilaiAkhir::where('mahasiswa_id', $mhs->id)->where('mata_kuliah_id', $mataKuliahId)->exists();

            if ($hasNilai) {
                self::prosesNilaiMahasiswa($mhs->id, $mataKuliahId);
            }
        }
    }
}
