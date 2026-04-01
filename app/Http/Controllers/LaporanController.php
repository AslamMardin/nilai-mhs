<?php

namespace App\Http\Controllers;

use App\Models\Kampus;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\Mahasiswa;
use App\Models\NilaiAkhir;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    // ─────────────────────────────────────────────────────
    // LAPORAN: NILAI AKHIR PER KELAS
    // ─────────────────────────────────────────────────────

    public function nilaiPerKelas(Request $request)
    {
        $kelasId = $request->get('kelas_id');

        $kelas = Kelas::with('kampus')->when($kelasId, fn($q) => $q->where('id', $kelasId))->get();

        $data = collect();

        if ($kelasId) {
            $kelasSelected = Kelas::with(['kampus', 'mataKuliah.nilaiAkhir.mahasiswa'])->findOrFail($kelasId);

            $data = $kelasSelected->mahasiswa->map(function ($mhs) use ($kelasSelected) {
                $nilaiPerMatkul = $kelasSelected->mataKuliah->mapWithKeys(function ($mk) use ($mhs) {
                    $na = NilaiAkhir::where('mahasiswa_id', $mhs->id)
                            ->where('mata_kuliah_id', $mk->id)
                            ->first();

                    return [$mk->kode => [
                        'nilai_akhir'      => $na?->nilai_akhir ?? '-',
                        'huruf_mutu'       => $na?->huruf_mutu ?? '-',
                        'status_kelulusan' => $na?->status_kelulusan ?? 'belum_dinilai',
                    ]];
                });

                return [
                    'mahasiswa'        => $mhs,
                    'nilai_per_matkul' => $nilaiPerMatkul,
                    'rata_rata'        => $mhs->ipk,
                ];
            });
        }

        return view('laporan.nilai-per-kelas', compact('kelas', 'kelasId', 'data'));
    }

    // ─────────────────────────────────────────────────────
    // LAPORAN: REKAP PER KAMPUS
    // ─────────────────────────────────────────────────────

    public function rekapKampus()
    {
        $kampusList = Kampus::with(['kelas', 'mataKuliah'])->get();

        $statistik = $kampusList->map(function ($kampus) {
            $totalMahasiswa = $kampus->mahasiswa()->count();

            $nilaiAkhirList = NilaiAkhir::whereHas('mahasiswa', fn($q) => $q->where('kampus_id', $kampus->id))
                ->get();

            $lulus      = $nilaiAkhirList->where('status_kelulusan', 'lulus')->count();
            $tidakLulus = $nilaiAkhirList->where('status_kelulusan', 'tidak_lulus')->count();
            $rataRata   = $nilaiAkhirList->avg('nilai_akhir') ?? 0;

            return [
                'kampus'         => $kampus,
                'total_mahasiswa' => $totalMahasiswa,
                'total_lulus'    => $lulus,
                'total_gagal'    => $tidakLulus,
                'rata_rata'      => round($rataRata, 2),
                'persentase_lulus' => $nilaiAkhirList->count() > 0
                    ? round(($lulus / $nilaiAkhirList->count()) * 100, 1)
                    : 0,
            ];
        });

        return view('laporan.rekap-kampus', compact('statistik'));
    }

    // ─────────────────────────────────────────────────────
    // LAPORAN: TRANSKRIP NILAI MAHASISWA
    // ─────────────────────────────────────────────────────

    public function transkripMahasiswa(Request $request)
    {
        $nim = $request->get('nim');
        $mahasiswa = null;
        $transkrip = collect();

        if ($nim) {
            $mahasiswa = Mahasiswa::with(['kampus', 'kelas'])->where('nim', $nim)->firstOrFail();

            $transkrip = NilaiAkhir::with(['mataKuliah'])
                ->where('mahasiswa_id', $mahasiswa->id)
                ->get()
                ->map(function ($na) {
                    return [
                        'mata_kuliah'  => $na->mataKuliah->nama,
                        'kode'         => $na->mataKuliah->kode,
                        'sks'          => $na->mataKuliah->sks,
                        'nilai_teori'  => $na->nilai_teori,
                        'nilai_prak'   => $na->nilai_praktikum,
                        'nilai_akhir'  => $na->nilai_akhir,
                        'huruf_mutu'   => $na->huruf_mutu,
                        'kehadiran'    => $na->persentase_kehadiran . '%',
                        'status'       => $na->status_kelulusan,
                    ];
                });
        }

        return view('laporan.transkrip', compact('mahasiswa', 'transkrip', 'nim'));
    }

    // ─────────────────────────────────────────────────────
    // EXPORT LAPORAN KE PDF / EXCEL (placeholder)
    // ─────────────────────────────────────────────────────

    public function exportPdf(int $mataKuliahId)
    {
        $mataKuliah = MataKuliah::with(['kampus', 'kelas'])->findOrFail($mataKuliahId);

        $rekap = NilaiAkhir::with(['mahasiswa'])
            ->where('mata_kuliah_id', $mataKuliahId)
            ->orderBy('nilai_akhir', 'desc')
            ->get();

        // Gunakan paket seperti barryvdh/laravel-dompdf
        // $pdf = PDF::loadView('laporan.pdf.nilai', compact('mataKuliah', 'rekap'));
        // return $pdf->download("nilai_{$mataKuliah->kode}.pdf");

        // Placeholder response untuk demo
        return response()->json([
            'message'    => 'Export PDF dibutuhkan paket barryvdh/laravel-dompdf',
            'mataKuliah' => $mataKuliah->nama,
            'total'      => $rekap->count(),
        ]);
    }
}
