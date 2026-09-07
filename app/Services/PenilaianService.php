<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\BobotNilai;
use App\Models\MataKuliah;
use App\Models\NilaiAkhir;
use App\Models\NilaiPraktikum;
use App\Models\NilaiTeori;
use Illuminate\Support\Facades\DB;

class PenilaianService
{
    /**
     * Hitung Nilai Teori (Nilai Akademik Teori)
     * Rumus: (Kehadiran * 0.25) + (Tugas * 0.20) + (UTS * 0.25) + (UAS * 0.30)
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
     * Hitung Nilai Akademik berdasarkan jenis mata kuliah:
     * - teori: NA Teori
     * - praktikum: NA Praktikum
     * - teori_praktikum: (NA Teori * 0.5) + (NA Praktikum * 0.5)
     */
    public static function hitungNilaiAkademik(string $jenisMk, float $naTeori, float $naPraktikum): float
    {
        return match ($jenisMk) {
            'teori'           => round($naTeori, 2),
            'praktikum'       => round($naPraktikum, 2),
            'teori_praktikum' => round(($naTeori * 0.5) + ($naPraktikum * 0.5), 2),
            default           => round($naTeori, 2),
        };
    }

    /**
     * Batas Nilai Minimum berdasarkan persentase kehadiran:
     * >= 95%      -> 85
     * 90 - 94.99% -> 75
     * 80 - 89.99% -> 65
     * 75 - 79.99% -> 55
     * < 75%       -> null (Tidak Lulus / tidak ada minimum)
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
     * Jika Kehadiran >= 75%: MAX(Nilai Akademik, Nilai Minimum Kehadiran)
     * Jika Kehadiran < 75%: Nilai Akademik (nilai asli tetap disimpan, tidak di-nol-kan)
     * Nilai Akhir dibatasi 0 <= Nilai Akhir <= 100
     */
    public static function hitungNilaiAkhir(float $nilaiAkademik, float $persenHadir): float
    {
        if ($persenHadir >= 75.0) {
            $minKehadiran = self::hitungMinimumKehadiran($persenHadir);
            $nilaiAkhir = $minKehadiran !== null ? max($nilaiAkademik, $minKehadiran) : $nilaiAkademik;
        } else {
            $nilaiAkhir = $nilaiAkademik;
        }

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
     * LULUS hanya jika: Kehadiran >= 75% DAN Nilai Akhir >= 55
     */
    public static function tentukanStatusKelulusan(float $persenHadir, float $nilaiAkhir): string
    {
        return ($persenHadir >= 75.0 && $nilaiAkhir >= 55.0) ? 'lulus' : 'tidak_lulus';
    }

    /**
     * Alasan Ketidaklulusan:
     * - Kehadiran < 75%: "Kehadiran 74% (minimal 75%)"
     * - Nilai akhir < 55: "Nilai akhir 50 (minimal 55)"
     * - Keduanya: "Kehadiran 70% (minimal 75%); Nilai akhir 50 (minimal 55)"
     * - Lulus: null
     */
    public static function tentukanKeteranganGagal(float $persenHadir, float $nilaiAkhir): ?string
    {
        $gagalKehadiran = $persenHadir < 75.0;
        $gagalNilai     = $nilaiAkhir < 55.0;

        if (!$gagalKehadiran && !$gagalNilai) {
            return null;
        }

        $fmtHadir = (float) $persenHadir;
        $fmtNilai = (float) $nilaiAkhir;

        if ($gagalKehadiran && $gagalNilai) {
            return "Kehadiran {$fmtHadir}% (minimal 75%); Nilai akhir {$fmtNilai} (minimal 55)";
        }

        if ($gagalKehadiran) {
            return "Kehadiran {$fmtHadir}% (minimal 75%)";
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
        $np = NilaiPraktikum::where('mahasiswa_id', $mahasiswaId)->where('mata_kuliah_id', $mataKuliahId)->first();

        $naT = 0.0;
        if ($mk->hasTeori() && $nt) {
            $naT = self::hitungNilaiTeori($persenHadir, (float)$nt->tugas, (float)$nt->uts, (float)$nt->uas);
            if ($nt->nilai_akhir_teori != $naT) {
                $nt->nilai_akhir_teori = $naT;
                $nt->save();
            }
        }

        $naP = $np ? (float)$np->nilai_praktikum : 0.0;

        $nilaiAkademik   = self::hitungNilaiAkademik($mk->jenis, $naT, $naP);
        $nilaiAkhirFinal = self::hitungNilaiAkhir($nilaiAkademik, $persenHadir);
        $hurufMutu       = self::konversiHurufMutu($nilaiAkhirFinal);
        $status          = self::tentukanStatusKelulusan($persenHadir, $nilaiAkhirFinal);
        $keteranganGagal = self::tentukanKeteranganGagal($persenHadir, $nilaiAkhirFinal);

        $record = NilaiAkhir::updateOrCreate(
            ['mahasiswa_id' => $mahasiswaId, 'mata_kuliah_id' => $mataKuliahId],
            [
                'nilai_teori'          => $naT,
                'nilai_praktikum'      => $naP,
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
                || NilaiPraktikum::where('mahasiswa_id', $mahasiswaId)->where('mata_kuliah_id', $mataKuliahId)->exists()
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
                || NilaiPraktikum::where('mahasiswa_id', $mhs->id)->where('mata_kuliah_id', $mataKuliahId)->exists()
                || NilaiAkhir::where('mahasiswa_id', $mhs->id)->where('mata_kuliah_id', $mataKuliahId)->exists();

            if ($hasNilai) {
                self::prosesNilaiMahasiswa($mhs->id, $mataKuliahId);
            }
        }
    }
}
