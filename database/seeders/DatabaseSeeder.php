<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Enrollment;
use App\Models\Institusi;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\Pertemuan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // ── 1. Institusi ────────────────────────────────────────────────
            $itbm  = Institusi::create(['nama' => 'Institut Teknologi dan Bisnis Muhammadiyah', 'kode' => 'ITBM',  'alamat' => 'Jeneponto']);
            $stain = Institusi::create(['nama' => 'Sekolah Tinggi Agama Islam Negeri Majene',   'kode' => 'STAIN', 'alamat' => 'Majene']);

            // ── 2. Mata Kuliah ───────────────────────────────────────────────
            $mataKuliah = [
                MataKuliah::create(['kode' => 'MK001', 'nama' => 'Algoritma & Pemrograman',   'sks' => 3, 'jenis' => 'teori_praktikum']),
                MataKuliah::create(['kode' => 'MK002', 'nama' => 'Basis Data',                 'sks' => 3, 'jenis' => 'teori_praktikum']),
                MataKuliah::create(['kode' => 'MK003', 'nama' => 'Pemrograman Web',            'sks' => 3, 'jenis' => 'teori_praktikum']),
                MataKuliah::create(['kode' => 'MK004', 'nama' => 'Pendidikan Agama Islam',     'sks' => 2, 'jenis' => 'teori']),
                MataKuliah::create(['kode' => 'MK005', 'nama' => 'Bahasa Indonesia',           'sks' => 2, 'jenis' => 'teori']),
            ];

            // ── 3. Kelas ─────────────────────────────────────────────────────
            // ITBM: 3 kelas
            $kelasITBM = [];
            foreach (['A', 'B', 'C'] as $nama) {
                $kelasITBM[$nama] = Kelas::create([
                    'institusi_id'     => $itbm->id,
                    'nama'             => "Kelas {$nama}",
                    'kode'             => "ITBM-{$nama}-2024",
                    'semester'         => 'genap',
                    'tahun_akademik'   => 2024,
                    'jumlah_pertemuan' => 16,
                ]);
            }

            // STAIN: 2 kelas
            $kelasSTAIN = [];
            foreach (['I', 'II'] as $nama) {
                $kelasSTAIN[$nama] = Kelas::create([
                    'institusi_id'     => $stain->id,
                    'nama'             => "Kelas {$nama}",
                    'kode'             => "STAIN-{$nama}-2024",
                    'semester'         => 'genap',
                    'tahun_akademik'   => 2024,
                    'jumlah_pertemuan' => 16,
                ]);
            }

            // ── 4. Mahasiswa ─────────────────────────────────────────────────
            // ITBM: 5 mahasiswa per kelas = 15 total
            $mahasiswaITBM = [];
            foreach (['A', 'B', 'C'] as $kode) {
                for ($i = 1; $i <= 5; $i++) {
                    $nim = "ITBM{$kode}2024" . str_pad($i, 3, '0', STR_PAD_LEFT);
                    $mahasiswaITBM[$kode][] = Mahasiswa::create([
                        'institusi_id'  => $itbm->id,
                        'nim'           => $nim,
                        'nama'          => "Mahasiswa ITBM {$kode}{$i}",
                        'jenis_kelamin' => $i % 2 === 0 ? 'P' : 'L',
                        'status'        => 'aktif',
                    ]);
                }
            }

            // STAIN: 5 mahasiswa per kelas = 10 total
            $mahasiswaSTAIN = [];
            foreach (['I', 'II'] as $kode) {
                for ($i = 1; $i <= 5; $i++) {
                    $nim = "STAIN{$kode}2024" . str_pad($i, 3, '0', STR_PAD_LEFT);
                    $mahasiswaSTAIN[$kode][] = Mahasiswa::create([
                        'institusi_id'  => $stain->id,
                        'nim'           => $nim,
                        'nama'          => "Mahasiswa STAIN {$kode}-{$i}",
                        'jenis_kelamin' => $i % 2 === 0 ? 'P' : 'L',
                        'status'        => 'aktif',
                    ]);
                }
            }

            // ── 5. Enrollment + Pertemuan + Absensi + Nilai (contoh) ─────────
            // Gunakan 1 mata kuliah per kelas agar seeder tidak terlalu berat
            $this->seedKelas($kelasITBM['A'], $mataKuliah[0], $mahasiswaITBM['A']);
            $this->seedKelas($kelasITBM['B'], $mataKuliah[1], $mahasiswaITBM['B']);
            $this->seedKelas($kelasITBM['C'], $mataKuliah[2], $mahasiswaITBM['C']);
            $this->seedKelas($kelasSTAIN['I'],  $mataKuliah[3], $mahasiswaSTAIN['I']);
            $this->seedKelas($kelasSTAIN['II'], $mataKuliah[4], $mahasiswaSTAIN['II']);
        });
    }

    // ────────────────────────────────────────────────────────────────────────
    private function seedKelas(Kelas $kelas, MataKuliah $mk, array $listMahasiswa): void
    {
        $statusAbsensi = ['H', 'H', 'H', 'H', 'T', 'S', 'I', 'A'];

        // Buat 16 pertemuan
        $pertemuan = [];
        for ($ke = 1; $ke <= 16; $ke++) {
            $pertemuan[] = Pertemuan::create([
                'kelas_id'       => $kelas->id,
                'mata_kuliah_id' => $mk->id,
                'ke'             => $ke,
                'tanggal'        => now()->subWeeks(16 - $ke),
                'topik'          => "Pertemuan ke-{$ke}: Topik {$mk->nama}",
            ]);
        }

        foreach ($listMahasiswa as $mhs) {
            // Enrollment
            $enrollment = Enrollment::create([
                'mahasiswa_id'   => $mhs->id,
                'kelas_id'       => $kelas->id,
                'mata_kuliah_id' => $mk->id,
                'tanggal_daftar' => now()->subMonths(4),
                'status'         => 'aktif',
            ]);

            // Absensi (acak tapi lebih banyak Hadir)
            foreach ($pertemuan as $p) {
                $status = $statusAbsensi[array_rand($statusAbsensi)];
                Absensi::create([
                    'enrollment_id' => $enrollment->id,
                    'pertemuan_id'  => $p->id,
                    'status'        => $status,
                ]);
            }

            // Nilai komponen
            $nilai = Nilai::create([
                'enrollment_id'   => $enrollment->id,
                'keaktifan'       => $mk->hasTeori() ? rand(60, 95) : null,
                'tugas'           => $mk->hasTeori() ? rand(60, 95) : null,
                'uts'             => $mk->hasTeori() ? rand(55, 90) : null,
                'uas'             => $mk->hasTeori() ? rand(55, 90) : null,
                'nilai_praktikum' => $mk->hasPraktikum() ? rand(60, 95) : null,
            ]);

            // Hitung otomatis
            $nilai->kalkulasiDanSimpan();
        }
    }
}
