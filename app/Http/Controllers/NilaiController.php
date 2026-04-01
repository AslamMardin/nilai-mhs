<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\MataKuliah;
use App\Models\Mahasiswa;
use App\Models\NilaiTeori;
use App\Models\NilaiPraktikum;
use App\Models\NilaiAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    // ─────────────────────────────────────────────────────
    // TAMPIL DAFTAR NILAI PER MATA KULIAH
    // ─────────────────────────────────────────────────────

    public function index(int $mataKuliahId)
    {
        $mataKuliah = MataKuliah::with(['kampus', 'kelas'])->findOrFail($mataKuliahId);

        // Ambil semua mahasiswa terdaftar di matkul ini
        $mahasiswaList = $mataKuliah->mahasiswa()
            ->with([
                'nilaiTeori' => fn($q) => $q->where('mata_kuliah_id', $mataKuliahId),
                'nilaiPraktikum' => fn($q) => $q->where('mata_kuliah_id', $mataKuliahId),
                'nilaiAkhir' => fn($q) => $q->where('mata_kuliah_id', $mataKuliahId),
            ])
            ->get();

        return view('nilai.index', compact('mataKuliah', 'mahasiswaList'));
    }

    // ─────────────────────────────────────────────────────
    // FORM INPUT NILAI TEORI
    // ─────────────────────────────────────────────────────

    public function formTeori(int $mataKuliahId)
    {
        $mataKuliah = MataKuliah::with('mahasiswa')->findOrFail($mataKuliahId);

        abort_unless($mataKuliah->hasTeori(), 403, 'Mata kuliah ini tidak memiliki komponen teori.');

        $nilaiTeoriData = NilaiTeori::where('mata_kuliah_id', $mataKuliahId)
            ->pluck(null, 'mahasiswa_id');

        return view('nilai.form-teori', compact('mataKuliah', 'nilaiTeoriData'));
    }

    // ─────────────────────────────────────────────────────
    // SIMPAN NILAI TEORI (BULK)
    // ─────────────────────────────────────────────────────

    public function simpanTeori(Request $request, int $mataKuliahId)
    {
        $mataKuliah = MataKuliah::findOrFail($mataKuliahId);

        $request->validate([
            'nilai'                  => 'required|array',
            'nilai.*.mahasiswa_id'   => 'required|exists:mahasiswa,id',
            'nilai.*.keaktifan'      => 'required|numeric|min:0|max:100',
            'nilai.*.tugas'          => 'required|numeric|min:0|max:100',
            'nilai.*.uts'            => 'required|numeric|min:0|max:100',
            'nilai.*.uas'            => 'required|numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($request, $mataKuliahId) {
            foreach ($request->nilai as $data) {
                // 1. Simpan & hitung nilai teori
                NilaiTeori::simpanDanHitung([
                    'mahasiswa_id'   => $data['mahasiswa_id'],
                    'mata_kuliah_id' => $mataKuliahId,
                    'keaktifan'      => $data['keaktifan'],
                    'tugas'          => $data['tugas'],
                    'uts'            => $data['uts'],
                    'uas'            => $data['uas'],
                ]);

                // 2. Hitung & update nilai akhir
                $this->hitungDanSimpanNilaiAkhir($data['mahasiswa_id'], $mataKuliahId);
            }
        });

        return redirect()
            ->route('nilai.index', $mataKuliahId)
            ->with('success', 'Nilai teori berhasil disimpan.');
    }

    // ─────────────────────────────────────────────────────
    // SIMPAN NILAI PRAKTIKUM (BULK)
    // ─────────────────────────────────────────────────────

    public function simpanPraktikum(Request $request, int $mataKuliahId)
    {
        $mataKuliah = MataKuliah::findOrFail($mataKuliahId);

        $request->validate([
            'nilai'                  => 'required|array',
            'nilai.*.mahasiswa_id'   => 'required|exists:mahasiswa,id',
            'nilai.*.nilai_praktikum' => 'required|numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($request, $mataKuliahId) {
            foreach ($request->nilai as $data) {
                // 1. Simpan nilai praktikum
                NilaiPraktikum::updateOrCreate(
                    [
                        'mahasiswa_id'   => $data['mahasiswa_id'],
                        'mata_kuliah_id' => $mataKuliahId,
                    ],
                    ['nilai_praktikum' => $data['nilai_praktikum']]
                );

                // 2. Hitung & update nilai akhir
                $this->hitungDanSimpanNilaiAkhir($data['mahasiswa_id'], $mataKuliahId);
            }
        });

        return redirect()
            ->route('nilai.index', $mataKuliahId)
            ->with('success', 'Nilai praktikum berhasil disimpan.');
    }

    // ─────────────────────────────────────────────────────
    // INTI: HITUNG & SIMPAN NILAI AKHIR
    // ─────────────────────────────────────────────────────

    /**
     * Menghitung nilai akhir dengan:
     * 1. Mengambil nilai teori (jika ada)
     * 2. Mengambil nilai praktikum (jika ada)
     * 3. Kombinasi 50:50 jika ada keduanya,
     *    atau 100% teori jika hanya teori,
     *    atau 100% praktikum jika hanya praktikum
     * 4. Hitung poin & persentase kehadiran
     * 5. Tentukan status kelulusan
     */
    public function hitungDanSimpanNilaiAkhir(int $mahasiswaId, int $mataKuliahId): NilaiAkhir
    {
        $mataKuliah = MataKuliah::findOrFail($mataKuliahId);

        // ── Ambil nilai komponen ──────────────────────────
        $nilaiTeori      = NilaiTeori::where('mahasiswa_id', $mahasiswaId)
                            ->where('mata_kuliah_id', $mataKuliahId)
                            ->first();

        $nilaiPraktikum  = NilaiPraktikum::where('mahasiswa_id', $mahasiswaId)
                            ->where('mata_kuliah_id', $mataKuliahId)
                            ->first();

        $naTeori     = $nilaiTeori?->nilai_akhir_teori ?? 0;
        $naPraktikum = $nilaiPraktikum?->nilai_praktikum ?? 0;

        // ── Hitung Nilai Akhir ────────────────────────────
        // Logika penentuan bobot berdasarkan jenis matkul
        $nilaiAkhir = match($mataKuliah->jenis) {
            'teori'           => $naTeori,
            'praktikum'       => $naPraktikum,
            'teori_praktikum' => ($naTeori * 0.5) + ($naPraktikum * 0.5), // 50:50
            default           => $naTeori,
        };
        $nilaiAkhir = round($nilaiAkhir, 2);

        // ── Hitung Kehadiran ──────────────────────────────
        $poinKehadiran = Absensi::hitungPoinKehadiran($mahasiswaId, $mataKuliahId);
        $persentase    = Absensi::hitungPersentaseKehadiran(
            $mahasiswaId,
            $mataKuliahId,
            $mataKuliah->total_pertemuan
        );
        $lolosKehadiran = $persentase >= 75.0;

        // ── Tentukan Status Kelulusan ─────────────────────
        $keteranganGagal = null;
        if (!$lolosKehadiran) {
            $status = 'tidak_lulus';
            $keteranganGagal = "Kehadiran {$persentase}% (minimal 75%)";
        } elseif ($nilaiAkhir < 55) {
            $status = 'tidak_lulus';
            $keteranganGagal = "Nilai akhir {$nilaiAkhir} (minimal 55 / huruf D)";
        } else {
            $status = 'lulus';
        }

        // ── Simpan ke Tabel nilai_akhir ───────────────────
        $record = NilaiAkhir::updateOrCreate(
            [
                'mahasiswa_id'   => $mahasiswaId,
                'mata_kuliah_id' => $mataKuliahId,
            ],
            [
                'nilai_teori'          => $naTeori,
                'nilai_praktikum'      => $naPraktikum,
                'nilai_akhir'          => $nilaiAkhir,
                'huruf_mutu'           => NilaiAkhir::konversiHurufMutu($nilaiAkhir),
                'persentase_kehadiran' => $persentase,
                'poin_kehadiran'       => $poinKehadiran,
                'status_kelulusan'     => $status,
                'keterangan_gagal'     => $keteranganGagal,
            ]
        );

        // ── Perbarui status pendaftaran ───────────────────
        DB::table('pendaftaran_mahasiswa')
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('mata_kuliah_id', $mataKuliahId)
            ->update(['status' => $status === 'lulus' ? 'lulus' : 'tidak_lulus']);

        return $record;
    }

    // ─────────────────────────────────────────────────────
    // REKAP NILAI PER MATA KULIAH (untuk laporan)
    // ─────────────────────────────────────────────────────

    public function rekapNilai(int $mataKuliahId)
    {
        $mataKuliah = MataKuliah::with(['kampus', 'kelas'])->findOrFail($mataKuliahId);

        $rekap = NilaiAkhir::with('mahasiswa')
            ->where('mata_kuliah_id', $mataKuliahId)
            ->orderBy('nilai_akhir', 'desc')
            ->get();

        // Statistik distribusi nilai
        $distribusiHuruf = $rekap->groupBy('huruf_mutu')
            ->map(fn($g) => $g->count())
            ->toArray();

        $statKelulusan = [
            'lulus'       => $rekap->where('status_kelulusan', 'lulus')->count(),
            'tidak_lulus' => $rekap->where('status_kelulusan', 'tidak_lulus')->count(),
            'total'       => $rekap->count(),
        ];

        return view('laporan.rekap-nilai', compact(
            'mataKuliah',
            'rekap',
            'distribusiHuruf',
            'statKelulusan'
        ));
    }
}
