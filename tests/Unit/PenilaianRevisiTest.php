<?php

namespace Tests\Unit;

use App\Services\PenilaianService;
use PHPUnit\Framework\TestCase;

class PenilaianRevisiTest extends TestCase
{
    /**
     * Pengujian Wajib (Bagian 15 pada penilaian.md)
     */
    public function test_tabel_pengujian_wajib()
    {
        $cases = [
            // [kehadiran, akademik, expectedNA, expectedHuruf, expectedStatus]
            [100.0, 60.0, 85.0, 'A', 'lulus'],
            [97.0,  80.0, 85.0, 'A', 'lulus'],
            [92.0,  60.0, 75.0, 'B', 'lulus'],
            [90.0,  80.0, 80.0, 'B', 'lulus'],
            [85.0,  60.0, 65.0, 'C', 'lulus'],
            [85.0,  90.0, 90.0, 'A', 'lulus'],
            [77.0,  50.0, 55.0, 'D', 'lulus'],
            [74.0,  95.0, 95.0, 'A', 'tidak_lulus'],
        ];

        foreach ($cases as [$hadir, $akademik, $expNA, $expHuruf, $expStatus]) {
            $na = PenilaianService::hitungNilaiAkhir($akademik, $hadir);
            $huruf = PenilaianService::konversiHurufMutu($na);
            $status = PenilaianService::tentukanStatusKelulusan($hadir, $na);

            $this->assertEquals($expNA, $na, "Nilai Akhir salah untuk Hadir={$hadir}, Akademik={$akademik}");
            $this->assertEquals($expHuruf, $huruf, "Huruf Mutu salah untuk Hadir={$hadir}, Akademik={$akademik}");
            $this->assertEquals($expStatus, $status, "Status Kelulusan salah untuk Hadir={$hadir}, Akademik={$akademik}");
        }
    }

    /**
     * Pengujian Batas Kehadiran (Bagian 16 pada penilaian.md)
     */
    public function test_pengujian_batas_kehadiran()
    {
        $this->assertEquals(75.0, PenilaianService::hitungMinimumKehadiran(94.99));
        $this->assertEquals(85.0, PenilaianService::hitungMinimumKehadiran(95.00));

        $this->assertEquals(65.0, PenilaianService::hitungMinimumKehadiran(89.99));
        $this->assertEquals(75.0, PenilaianService::hitungMinimumKehadiran(90.00));

        $this->assertEquals(55.0, PenilaianService::hitungMinimumKehadiran(79.99));
        $this->assertEquals(65.0, PenilaianService::hitungMinimumKehadiran(80.00));

        $this->assertNull(PenilaianService::hitungMinimumKehadiran(74.99));
        $this->assertEquals(55.0, PenilaianService::hitungMinimumKehadiran(75.00));
    }

    /**
     * Pengujian Rumus Bobot (Bagian 17 pada penilaian.md)
     * Hadir: 80, Tugas: 70, UTS: 60, UAS: 50 -> Akademik: 64, Min: 65, NA: 65, Huruf: C, Status: Lulus
     */
    public function test_pengujian_rumus_bobot()
    {
        $hadir = 80.0;
        $tugas = 70.0;
        $uts   = 60.0;
        $uas   = 50.0;

        $akademik = PenilaianService::hitungNilaiTeori($hadir, $tugas, $uts, $uas);
        $this->assertEquals(64.0, $akademik, "Nilai akademik teori harus 64");

        $na = PenilaianService::hitungNilaiAkhir($akademik, $hadir);
        $this->assertEquals(65.0, $na, "Nilai akhir harus MAX(64, 65) = 65");

        $huruf = PenilaianService::konversiHurufMutu($na);
        $this->assertEquals('C', $huruf, "Huruf mutu harus C");

        $status = PenilaianService::tentukanStatusKelulusan($hadir, $na);
        $this->assertEquals('lulus', $status, "Status harus lulus");
    }

    /**
     * Pengujian Tanpa Reward (Bagian 18 pada penilaian.md)
     */
    public function test_pengujian_tanpa_reward()
    {
        // Hadir 100%, Akademik 90 -> NA 90 (bukan 95)
        $na1 = PenilaianService::hitungNilaiAkhir(90.0, 100.0);
        $this->assertEquals(90.0, $na1);

        // Hadir 100%, Akademik 80 -> NA 85 (karena minimum kehadiran 85, bukan bonus +5)
        $na2 = PenilaianService::hitungNilaiAkhir(80.0, 100.0);
        $this->assertEquals(85.0, $na2);
    }

    /**
     * Pengujian Batas Nilai (Bagian 19 pada penilaian.md)
     * 0 <= Nilai Akhir <= 100
     */
    public function test_pengujian_batas_nilai()
    {
        $naOver = PenilaianService::hitungNilaiAkhir(120.0, 100.0);
        $this->assertEquals(100.0, $naOver);

        $naUnder = PenilaianService::hitungNilaiAkhir(-10.0, 50.0);
        $this->assertEquals(0.0, $naUnder);
    }

    /**
     * Pengujian Alasan Ketidaklulusan (Bagian 6 pada penilaian.md)
     */
    public function test_pengujian_alasan_ketidaklulusan()
    {
        // Hadir < 75%
        $ket1 = PenilaianService::tentukanKeteranganGagal(74.0, 95.0);
        $this->assertEquals('Kehadiran 74% (minimal 75%)', $ket1);

        // Nilai akhir < 55
        $ket2 = PenilaianService::tentukanKeteranganGagal(80.0, 50.0);
        $this->assertEquals('Nilai akhir 50 (minimal 55)', $ket2);

        // Keduanya tidak terpenuhi
        $ket3 = PenilaianService::tentukanKeteranganGagal(70.0, 50.0);
        $this->assertEquals('Kehadiran 70% (minimal 75%); Nilai akhir 50 (minimal 55)', $ket3);

        // Lulus
        $ket4 = PenilaianService::tentukanKeteranganGagal(80.0, 65.0);
        $this->assertNull($ket4);
    }
}
