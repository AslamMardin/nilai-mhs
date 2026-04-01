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


return view('dashboard.index', compact(
    'kampusAktif',
    'semuaKampus',
    'totalMahasiswa',
    'totalMataKuliah',
    'totalKelas',
    'persenLulus',
    'distribusi', // tetap ini
    'statLulus',
    'mataKuliahList',
    'kelasList'
));
    }
}
