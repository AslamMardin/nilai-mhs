<?php

namespace App\Http\Controllers;

use App\Models\{Kampus, Kelas, MataKuliah, Mahasiswa, NilaiAkhir};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function nilaiKelas(Request $request)
    {
        $kampusId = session('kampus_id') ?? Auth::user()->kampus_id;
        $kelasList = Kelas::with('kampus')->where('kampus_id', $kampusId)->get();
        $kelasId  = $request->kelas_id;
        $kelasSelected = null;
        $data = collect();

        if ($kelasId) {
            $kelasSelected = Kelas::with(['kampus', 'mataKuliah', 'mahasiswa'])->findOrFail($kelasId);
            $data = $kelasSelected->mahasiswa->map(function ($mhs) use ($kelasSelected) {
                $nilai = $kelasSelected->mataKuliah->mapWithKeys(function ($mk) use ($mhs) {
                    $na = NilaiAkhir::where('mahasiswa_id', $mhs->id)->where('mata_kuliah_id', $mk->id)->first();
                    return [$mk->kode => ['nilai_akhir' => $na?->nilai_akhir ?? '—', 'huruf_mutu' => $na?->huruf_mutu ?? '—', 'status_kelulusan' => $na?->status_kelulusan]];
                });
                $rataRata = $kelasSelected->mataKuliah->map(function ($mk) use ($mhs) {
                    $na = NilaiAkhir::where('mahasiswa_id', $mhs->id)->where('mata_kuliah_id', $mk->id)->first();
                    return is_numeric($na?->nilai_akhir) ? $na->nilai_akhir : null;
                })->filter()->avg() ?? 0;
                return ['mahasiswa' => $mhs, 'nilai' => $nilai, 'rata_rata' => round($rataRata, 2)];
            });
        }
        return view('laporan.nilai-kelas', compact('kelasList', 'kelasId', 'kelasSelected', 'data'));
    }

    public function rekap()
    {
        $kampusList = Kampus::with(['kelas.mahasiswa', 'kelas.mataKuliah', 'mataKuliah'])->get();
        $statistik  = $kampusList->map(function ($k) {
            $totalMhs = $k->mahasiswa()->count();
            $nilaiQ   = NilaiAkhir::whereHas('mahasiswa', fn($q) => $q->where('kampus_id', $k->id));
            $lulus    = (clone $nilaiQ)->where('status_kelulusan', 'lulus')->count();
            $gagal    = (clone $nilaiQ)->where('status_kelulusan', 'tidak_lulus')->count();
            $rataRata = round((clone $nilaiQ)->avg('nilai_akhir') ?? 0, 2);
            $total    = $lulus + $gagal;
            return ['kampus' => $k, 'total_mahasiswa' => $totalMhs, 'total_lulus' => $lulus, 'total_gagal' => $gagal, 'rata_rata' => $rataRata, 'pct_lulus' => $total > 0 ? round($lulus / $total * 100) : 0];
        });
        return view('laporan.rekap', compact('statistik'));
    }

    public function transkrip(Request $request)
    {
        $nim = $request->nim;
        $mahasiswa = null;
        $transkrip = collect();
        if ($nim) {
            $mahasiswa = Mahasiswa::with(['kampus', 'kelas'])->where('nim', $nim)->first();
            if ($mahasiswa) {
                $transkrip = NilaiAkhir::with('mataKuliah')->where('mahasiswa_id', $mahasiswa->id)->get()->map(fn($na) => ['kode' => $na->mataKuliah->kode, 'nama' => $na->mataKuliah->nama, 'sks' => $na->mataKuliah->sks, 'nilai_teori' => $na->nilai_teori, 'nilai_prak' => $na->nilai_praktikum, 'nilai_akhir' => $na->nilai_akhir, 'huruf_mutu' => $na->huruf_mutu, 'kehadiran' => $na->persentase_kehadiran . '%', 'status' => $na->status_kelulusan]);
            }
        }
        return view('laporan.transkrip', compact('mahasiswa', 'transkrip', 'nim'));
    }

    public function rekapMk(MataKuliah $mataKuliah)
    {
        $mataKuliah->load(['kampus', 'kelas']);
        $rekap = NilaiAkhir::with('mahasiswa')->where('mata_kuliah_id', $mataKuliah->id)->get();
        $distribusiHuruf = $rekap->whereNotNull('huruf_mutu')->groupBy('huruf_mutu')->map->count()->toArray();
        return view('laporan.rekap-mk', compact('mataKuliah', 'rekap', 'distribusiHuruf'));
    }
}
