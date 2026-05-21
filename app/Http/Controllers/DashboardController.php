<?php

namespace App\Http\Controllers;

use App\Models\Kampus;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\NilaiAkhir;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $kampusId = session('kampus_id') ?? Auth::user()->kampus_id;

        // Stats utama

        $totalMahasiswa = Mahasiswa::where('kampus_id', $kampusId)->where('status', 'aktif')->count();
        $totalMataKuliah = MataKuliah::where('kampus_id', $kampusId)->count();
        $totalKelas = Kelas::where('kampus_id', $kampusId)->count();

        // Kelulusan
        $nilaiQuery = NilaiAkhir::whereHas('mahasiswa', fn ($q) => $q->where('kampus_id', $kampusId));
        $totalNilai = $nilaiQuery->clone()->whereIn('status_kelulusan', ['lulus', 'tidak_lulus'])->count();
        $totalLulus = $nilaiQuery->clone()->where('status_kelulusan', 'lulus')->count();
        $persenLulus = $totalNilai > 0 ? round(($totalLulus / $totalNilai) * 100) : 0;

        // Distribusi huruf mutu
        $distribusi = NilaiAkhir::whereHas('mahasiswa', fn ($q) => $q->where('kampus_id', $kampusId))
            ->whereNotNull('huruf_mutu')
            ->selectRaw('huruf_mutu, COUNT(*) as jml')
            ->groupBy('huruf_mutu')
            ->pluck('jml', 'huruf_mutu')
            ->toArray();

        $statLulus = [
            'lulus' => $totalLulus,
            'tidak_lulus' => $nilaiQuery->clone()->where('status_kelulusan', 'tidak_lulus')->count(),
        ];

        // Mata kuliah terbaru di kampus aktif
        $mataKuliahList = MataKuliah::with(['kelas', 'nilaiAkhir'])
            ->withCount('mahasiswa')
            ->where('kampus_id', $kampusId)
            ->latest()->take(8)->get();

        // Kelas di kampus aktif
        $kelasList = Kelas::withCount('mahasiswa')
            ->where('kampus_id', $kampusId)->get();

        $kampusAktif = $kampusId ? Kampus::find($kampusId) : null;
        $semuaKampus = Kampus::all();

        $rekapKelas = $kelasList->map(function ($kls) {

            $nilai = NilaiAkhir::whereHas('mahasiswa', function ($q) use ($kls) {
                $q->where('kelas_id', $kls->id);
            });

            $lulus = (clone $nilai)->where('status_kelulusan', 'lulus')->count();
            $tidak = (clone $nilai)->where('status_kelulusan', 'tidak_lulus')->count();

            $total = $lulus + $tidak;

            return [
                'kelas_id' => $kls->id,
                'pct_lulus' => $total > 0 ? round(($lulus / $total) * 100) : 0,
            ];
        });

        // Ranking Mahasiswa Global (Semua)
        $rankingMahasiswa = Mahasiswa::where('kampus_id', $kampusId)
            ->select('mahasiswa.*')
            ->selectSub(function ($q) {
                $q->from('nilai_akhir')
                    ->selectRaw('AVG(nilai_akhir)')
                    ->whereColumn('mahasiswa_id', 'mahasiswa.id');
            }, 'rata_nilai')
            ->orderByDesc('rata_nilai')
            ->take(10)
            ->get();

        // Ranking Mahasiswa per Kelas
        $rankingPerKelas = [];
        foreach ($kelasList as $kls) {
            $rankingPerKelas[$kls->id] = Mahasiswa::where('kelas_id', $kls->id)
                ->select('mahasiswa.*')
                ->selectSub(function ($q) {
                    $q->from('nilai_akhir')
                        ->selectRaw('AVG(nilai_akhir)')
                        ->whereColumn('mahasiswa_id', 'mahasiswa.id');
                }, 'rata_nilai')
                ->orderByDesc('rata_nilai')
                ->take(10)
                ->get();
        }

        // Generate Calendar Events
        $allMataKuliah = MataKuliah::with('kelas')
            // ->where('kampus_id', $kampusId)
            ->whereNotNull('tanggal_mulai')
            ->get();

        $calendarEvents = [];
        foreach ($allMataKuliah as $mk) {
            $startDate = Carbon::parse($mk->tanggal_mulai);
            $totalPertemuan = $mk->total_pertemuan ?? 14;

            for ($i = 0; $i < $totalPertemuan; $i++) {
                $currentDate = $startDate->copy()->addWeeks($i);
                $calendarEvents[] = [
                    'date' => $currentDate->format('Y-m-d'),
                    'title' => $mk->nama.' ('.$mk->kelas->nama.')',
                    'time' => ($mk->jam_mulai ? substr($mk->jam_mulai, 0, 5) : '').
                              ($mk->jam_selesai ? ' - '.substr($mk->jam_selesai, 0, 5) : ''),
                    'pertemuan' => $i + 1,
                ];
            }
        }

        return view('dashboard.index', compact(
            'kampusAktif',
            'semuaKampus',
            'totalMahasiswa',
            'totalMataKuliah',
            'totalKelas',
            'persenLulus',
            'distribusi',
            'statLulus',
            'mataKuliahList',
            'kelasList',
            'rekapKelas',
            'rankingMahasiswa',
            'rankingPerKelas',
            'calendarEvents'
        ));
    }
}
