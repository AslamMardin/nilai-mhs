<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── User Admin ──────────────────────────────────────────
        DB::table('users')->insert([
            'name'       => 'Administrator',
            'email'      => 'admin@penilaian.ac.id',
            'password'   => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ── Kampus ──────────────────────────────────────────────
        $kampusItbm = DB::table('kampus')->insertGetId([
            'nama'    => 'Institut Teknologi dan Bisnis Muhammadiyah',
            'kode'    => 'ITBM',
            'alamat'  => 'Jl. Poros Majene, Sulawesi Barat',
            'telepon' => '0422-123456',
        ]);

        $kampusStain = DB::table('kampus')->insertGetId([
            'nama'    => 'Sekolah Tinggi Agama Islam Negeri Majene',
            'kode'    => 'STAIN',
            'alamat'  => 'Jl. BPD, Majene, Sulawesi Barat',
            'telepon' => '0422-654321',
        ]);

        // ── Kelas ITBM (3 kelas) ────────────────────────────────
        $kelasItbm1 = DB::table('kelas')->insertGetId([
            'kampus_id'    => $kampusItbm,
            'nama'         => 'Teknik Informatika A',
            'kode'         => 'TI-A',
            'semester'     => 'ganjil',
            'tahun_ajaran' => 2024,
            'wali_kelas'   => 'Dr. Ahmad Fauzi, M.T',
        ]);

        $kelasItbm2 = DB::table('kelas')->insertGetId([
            'kampus_id'    => $kampusItbm,
            'nama'         => 'Teknik Informatika B',
            'kode'         => 'TI-B',
            'semester'     => 'ganjil',
            'tahun_ajaran' => 2024,
            'wali_kelas'   => 'Siti Rahmah, M.Kom',
        ]);

        $kelasItbm3 = DB::table('kelas')->insertGetId([
            'kampus_id'    => $kampusItbm,
            'nama'         => 'Sistem Informasi A',
            'kode'         => 'SI-A',
            'semester'     => 'ganjil',
            'tahun_ajaran' => 2024,
            'wali_kelas'   => 'Hj. Fatimah, M.Si',
        ]);

        // ── Kelas STAIN (2 kelas) ───────────────────────────────
        $kelasStain1 = DB::table('kelas')->insertGetId([
            'kampus_id'    => $kampusStain,
            'nama'         => 'Pendidikan Agama Islam A',
            'kode'         => 'PAI-A',
            'semester'     => 'ganjil',
            'tahun_ajaran' => 2024,
            'wali_kelas'   => 'Ustadz Ridwan, Lc., M.A',
        ]);

        $kelasStain2 = DB::table('kelas')->insertGetId([
            'kampus_id'    => $kampusStain,
            'nama'         => 'Ekonomi Syariah A',
            'kode'         => 'ES-A',
            'semester'     => 'ganjil',
            'tahun_ajaran' => 2024,
            'wali_kelas'   => 'Dr. Nur Hasanah, M.E',
        ]);

        // ── Mata Kuliah ITBM ────────────────────────────────────
        $mkAlgoritma = DB::table('mata_kuliah')->insertGetId([
            'kampus_id'       => $kampusItbm,
            'kelas_id'        => $kelasItbm1,
            'kode'            => 'TI101',
            'nama'            => 'Algoritma dan Pemrograman',
            'sks'             => 3,
            'jenis'           => 'teori_praktikum',
            'dosen'           => 'Dr. Ahmad Fauzi, M.T',
            'total_pertemuan' => 16,
        ]);

        $mkBasisData = DB::table('mata_kuliah')->insertGetId([
            'kampus_id'       => $kampusItbm,
            'kelas_id'        => $kelasItbm1,
            'kode'            => 'TI102',
            'nama'            => 'Basis Data',
            'sks'             => 3,
            'jenis'           => 'teori_praktikum',
            'dosen'           => 'Siti Rahmah, M.Kom',
            'total_pertemuan' => 16,
        ]);

        // Mata Kuliah STAIN
        $mkFiqih = DB::table('mata_kuliah')->insertGetId([
            'kampus_id'       => $kampusStain,
            'kelas_id'        => $kelasStain1,
            'kode'            => 'PAI201',
            'nama'            => 'Fiqih Kontemporer',
            'sks'             => 2,
            'jenis'           => 'teori',
            'dosen'           => 'Ustadz Ridwan, Lc., M.A',
            'total_pertemuan' => 16,
        ]);

        // ── Mahasiswa ITBM - Kelas TI-A (5 mahasiswa) ──────────
        $mahasiswaItbm = [];
        $namaMahasiswaItbm = [
            ['nim' => 'ITBM2024001', 'nama' => 'Andi Baso', 'jk' => 'L'],
            ['nim' => 'ITBM2024002', 'nama' => 'Siti Aisyah', 'jk' => 'P'],
            ['nim' => 'ITBM2024003', 'nama' => 'Muhammad Ridwan', 'jk' => 'L'],
            ['nim' => 'ITBM2024004', 'nama' => 'Nurul Hikmah', 'jk' => 'P'],
            ['nim' => 'ITBM2024005', 'nama' => 'Zulkifli', 'jk' => 'L'],
        ];

        foreach ($namaMahasiswaItbm as $mhs) {
            $mahasiswaItbm[] = DB::table('mahasiswa')->insertGetId([
                'kampus_id'     => $kampusItbm,
                'kelas_id'      => $kelasItbm1,
                'nim'           => $mhs['nim'],
                'nama'          => $mhs['nama'],
                'jenis_kelamin' => $mhs['jk'],
                'email'         => strtolower(str_replace(' ', '.', $mhs['nama'])) . '@student.itbm.ac.id',
                'status'        => 'aktif',
            ]);
        }

        // ── Mahasiswa STAIN - Kelas PAI-A (3 mahasiswa) ─────────
        $mahasiswaStain = [];
        $namaMahasiswaStain = [
            ['nim' => 'STAIN2024001', 'nama' => 'Ahmad Maulana', 'jk' => 'L'],
            ['nim' => 'STAIN2024002', 'nama' => 'Rahma Fitri', 'jk' => 'P'],
            ['nim' => 'STAIN2024003', 'nama' => 'Hamzah Al-Rasyid', 'jk' => 'L'],
        ];

        foreach ($namaMahasiswaStain as $mhs) {
            $mahasiswaStain[] = DB::table('mahasiswa')->insertGetId([
                'kampus_id'     => $kampusStain,
                'kelas_id'      => $kelasStain1,
                'nim'           => $mhs['nim'],
                'nama'          => $mhs['nama'],
                'jenis_kelamin' => $mhs['jk'],
                'email'         => strtolower(str_replace(' ', '.', $mhs['nama'])) . '@student.stain.ac.id',
                'status'        => 'aktif',
            ]);
        }

        // ── Pendaftaran: Mahasiswa ITBM ke 2 mata kuliah ─────────
        foreach ($mahasiswaItbm as $mhsId) {
            foreach ([$mkAlgoritma, $mkBasisData] as $mkId) {
                DB::table('pendaftaran_mahasiswa')->insert([
                    'mahasiswa_id'   => $mhsId,
                    'mata_kuliah_id' => $mkId,
                    'tahun_ajaran'   => 2024,
                    'semester'       => 'ganjil',
                    'status'         => 'aktif',
                ]);
            }
        }

        // ── Pendaftaran: Mahasiswa STAIN ke 1 mata kuliah ─────────
        foreach ($mahasiswaStain as $mhsId) {
            DB::table('pendaftaran_mahasiswa')->insert([
                'mahasiswa_id'   => $mhsId,
                'mata_kuliah_id' => $mkFiqih,
                'tahun_ajaran'   => 2024,
                'semester'       => 'ganjil',
                'status'         => 'aktif',
            ]);
        }

        // ── Absensi: 16 pertemuan untuk mahasiswa ITBM ──────────
        $statusSample = ['H', 'H', 'H', 'T', 'H', 'H', 'S', 'H', 'H', 'H', 'H', 'I', 'H', 'H', 'H', 'A'];

        foreach ($mahasiswaItbm as $idx => $mhsId) {
            foreach ([$mkAlgoritma] as $mkId) {
                for ($p = 1; $p <= 16; $p++) {
                    // Variasikan status agar berbeda tiap mahasiswa
                    $statusIdx = ($p + $idx) % count($statusSample);
                    DB::table('absensi')->insert([
                        'mahasiswa_id'   => $mhsId,
                        'mata_kuliah_id' => $mkId,
                        'pertemuan_ke'   => $p,
                        'tanggal'        => Carbon::now()->subWeeks(16 - $p)->toDateString(),
                        'status'         => $statusSample[$statusIdx],
                    ]);
                }
            }
        }

        // ── Nilai Teori: Mata Kuliah Algoritma (ITBM) ───────────
        $nilaiSample = [
            [80, 85, 78, 82],
            [90, 92, 88, 91],
            [70, 72, 65, 68],
            [85, 88, 80, 84],
            [75, 78, 72, 76],
        ];

        foreach ($mahasiswaItbm as $idx => $mhsId) {
            $n = $nilaiSample[$idx];
            $naTeori = round(($n[0]*0.20) + ($n[1]*0.20) + ($n[2]*0.25) + ($n[3]*0.35), 2);

            DB::table('nilai_teori')->insert([
                'mahasiswa_id'     => $mhsId,
                'mata_kuliah_id'   => $mkAlgoritma,
                'keaktifan'        => $n[0],
                'tugas'            => $n[1],
                'uts'              => $n[2],
                'uas'              => $n[3],
                'nilai_akhir_teori' => $naTeori,
            ]);

            // Nilai Praktikum
            DB::table('nilai_praktikum')->insert([
                'mahasiswa_id'   => $mhsId,
                'mata_kuliah_id' => $mkAlgoritma,
                'nilai_praktikum' => $n[0] + rand(-5, 5),
            ]);
        }

        $this->command->info('Seeder berhasil: 2 kampus, 5 kelas, 3 mata kuliah, 8 mahasiswa.');
    }
}
