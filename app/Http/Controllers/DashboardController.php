<?php

namespace App\Http\Controllers;

use App\Models\Kampus;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\NilaiAkhir;



class DashboardController extends Controller
{
    public function index()
    {
        // Statistik ringkas
        $totalMahasiswa = Mahasiswa::where('status', 'aktif')->count();
        $totalMataKuliah = MataKuliah::count();
        $totalKelas = Kelas::count();

        // Persentase kelulusan global
        $totalNilai = NilaiAkhir::whereIn('status_kelulusan', ['lulus', 'tidak_lulus'])->count();
        $totalLulus = NilaiAkhir::where('status_kelulusan', 'lulus')->count();
        $persenLulus = $totalNilai > 0 ? round(($totalLulus / $totalNilai) * 100) : 0;

        // Rekap per kampus
        $kampusList = Kampus::with(['kelas', 'mataKuliah'])->get();
        $rekapKampus = $kampusList->map(function ($kampus) {
            $totalMhs = $kampus->mahasiswa()->count();
            $nilaiList = NilaiAkhir::whereHas('mahasiswa', fn($q) => $q->where('kampus_id', $kampus->id))->get();
            $lulus = $nilaiList->where('status_kelulusan', 'lulus')->count();
            $total = $nilaiList->count();
            return [
                'kampus'           => $kampus,
                'total_mahasiswa'  => $totalMhs,
                'persentase_lulus' => $total > 0 ? round(($lulus / $total) * 100, 1) : 0,
            ];
        });

        // Distribusi huruf mutu global
        $distribusiHuruf = NilaiAkhir::selectRaw('huruf_mutu, COUNT(*) as jumlah')
            ->whereNotNull('huruf_mutu')
            ->groupBy('huruf_mutu')
            ->pluck('jumlah', 'huruf_mutu')
            ->toArray();

        $statLulus = [
            'lulus'       => NilaiAkhir::where('status_kelulusan', 'lulus')->count(),
            'tidak_lulus' => NilaiAkhir::where('status_kelulusan', 'tidak_lulus')->count(),
        ];

        // Mata kuliah terkini
        $mataKuliahTerkini = MataKuliah::with(['kampus', 'kelas', 'mahasiswa', 'nilaiAkhir'])
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard.index', compact(
            'totalMahasiswa',
            'totalMataKuliah',
            'totalKelas',
            'persenLulus',
            'rekapKampus',
            'distribusiHuruf',
            'statLulus',
            'mataKuliahTerkini'
        ));
    }
}
