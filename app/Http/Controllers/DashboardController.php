<?php namespace App\Http\Controllers;

use App\Models\Kampus;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\Mahasiswa;
use App\Models\NilaiAkhir;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $kampusId = session('kampus_id') ?? Auth::user()->kampus_id;

        // Stats utama
        $totalMahasiswa  = Mahasiswa::where('kampus_id', $kampusId)->where('status','aktif')->count();
        $totalMataKuliah = MataKuliah::where('kampus_id', $kampusId)->count();
        $totalKelas      = Kelas::where('kampus_id', $kampusId)->count();

        // Kelulusan
        $nilaiQuery = NilaiAkhir::whereHas('mahasiswa', fn($q) => $q->where('kampus_id', $kampusId));
        $totalNilai  = $nilaiQuery->clone()->whereIn('status_kelulusan',['lulus','tidak_lulus'])->count();
        $totalLulus  = $nilaiQuery->clone()->where('status_kelulusan','lulus')->count();
        $persenLulus = $totalNilai > 0 ? round(($totalLulus / $totalNilai) * 100) : 0;

        // Distribusi huruf mutu
        $distribusi = NilaiAkhir::whereHas('mahasiswa', fn($q) => $q->where('kampus_id', $kampusId))
            ->whereNotNull('huruf_mutu')
            ->selectRaw('huruf_mutu, COUNT(*) as jml')
            ->groupBy('huruf_mutu')
            ->pluck('jml','huruf_mutu')
            ->toArray();

        $statLulus = [
            'lulus'       => $totalLulus,
            'tidak_lulus' => $nilaiQuery->clone()->where('status_kelulusan','tidak_lulus')->count(),
        ];

        // Mata kuliah terbaru di kampus aktif
        $mataKuliahList = MataKuliah::with(['kelas','nilaiAkhir'])
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
        'kelas_id'  => $kls->id,
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

        // Mahasiswa Berisiko (Kehadiran < 75% atau Nilai Akhir < 55)
        $mahasiswaBerisiko = NilaiAkhir::with(['mahasiswa.kelas', 'mataKuliah'])
            ->whereHas('mahasiswa', fn($q) => $q->where('kampus_id', $kampusId))
            ->where(function($q) {
                $q->where('status_kelulusan', 'tidak_lulus')
                  ->orWhere('persentase_kehadiran', '<', 75.0)
                  ->orWhere('nilai_akhir', '<', 55);
            })
            ->latest()
            ->take(10)
            ->get();

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
            'mahasiswaBerisiko'
        ));
    }
}
