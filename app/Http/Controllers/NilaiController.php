<?php namespace App\Http\Controllers;
use App\Exports\NilaiTeoriExport;
use App\Exports\NilaiMataKuliahExport;
use App\Models\{Absensi,MataKuliah,NilaiTeori,NilaiAkhir};
use App\Models\{NilaiKeaktifanDetail, NilaiTugasDetail};
use App\Models\BobotNilai;
use App\Services\PenilaianService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth,DB};
use Maatwebsite\Excel\Facades\Excel;

class NilaiController extends Controller {
    public function pilih() {
        $kampusId = session('kampus_id') ?? Auth::user()->kampus_id;
        $mataKuliahList = MataKuliah::with('kelas')->where('kampus_id',$kampusId)
            ->withCount(['mahasiswa','nilaiAkhir'])->get();
        return view('nilai.pilih', compact('mataKuliahList'));
    }

    public function index(MataKuliah $mataKuliah) {
        $mataKuliah->load(['kampus','kelas']);
        $bobot = BobotNilai::first();
        $mahasiswaList = $mataKuliah->mahasiswa()->with([
            'nilaiTeori'     => fn($q)=>$q->where('mata_kuliah_id',$mataKuliah->id),
            'nilaiAkhir'     => fn($q)=>$q->where('mata_kuliah_id',$mataKuliah->id),
        ])->get();
        return view('nilai.index', compact('mataKuliah','mahasiswaList', 'bobot'));
    }

    public function exportExcel(MataKuliah $mataKuliah) {
        $mataKuliah->load(['kampus','kelas']);
        $mahasiswaList = $mataKuliah->mahasiswa()->with([
            'nilaiTeori'     => fn($q)=>$q->where('mata_kuliah_id',$mataKuliah->id),
            'nilaiAkhir'     => fn($q)=>$q->where('mata_kuliah_id',$mataKuliah->id),
        ])->get();

        return Excel::download(
            new NilaiMataKuliahExport($mataKuliah, $mahasiswaList),
            'Rekap-Nilai-' . $mataKuliah->kode . '.xlsx'
        );
    }

    public function exportPdf(MataKuliah $mataKuliah) {
        $mataKuliah->load(['kampus','kelas']);
        $mahasiswaList = $mataKuliah->mahasiswa()->with([
            'nilaiTeori'     => fn($q)=>$q->where('mata_kuliah_id',$mataKuliah->id),
            'nilaiAkhir'     => fn($q)=>$q->where('mata_kuliah_id',$mataKuliah->id),
        ])->get();

        $pdf = Pdf::loadView('nilai.export.rekap', [
            'mataKuliah' => $mataKuliah,
            'mahasiswaList' => $mahasiswaList
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Rekap-Nilai-' . $mataKuliah->kode . '.pdf');
    }

    public function formTeori(MataKuliah $mataKuliah) {
        abort_unless($mataKuliah->hasTeori(), 403, 'Mata kuliah ini tidak memiliki komponen teori.');
        $bobot = BobotNilai::first();
        $mataKuliah->load(['kampus','kelas','mahasiswa']);
        $nilaiTeoriData = NilaiTeori::where('mata_kuliah_id',$mataKuliah->id)->get()->keyBy('mahasiswa_id');
        return view('nilai.form-teori', compact('mataKuliah','nilaiTeoriData', 'bobot'));
    }

    public function simpanTeori(Request $request, MataKuliah $mataKuliah) {
        $request->validate([
            'nilai' => 'required|array',
            'nilai.*.mahasiswa_id' => 'required|exists:mahasiswa,id',
            'nilai.*.keaktifan' => 'nullable|numeric|min:0|max:100',
            'nilai.*.tugas' => 'nullable|numeric|min:0|max:100',
            'nilai.*.uts' => 'nullable|numeric|min:0|max:100',
            'nilai.*.uas' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::transaction(function() use ($request, $mataKuliah) {
            foreach ($request->nilai as $d) {
                NilaiTeori::simpan([
                    'mahasiswa_id' => $d['mahasiswa_id'],
                    'mata_kuliah_id' => $mataKuliah->id,
                    'keaktifan' => $d['keaktifan'] ?? 0,
                    'tugas' => $d['tugas'] ?? 0,
                    'uts' => $d['uts'] ?? 0,
                    'uas' => $d['uas'] ?? 0,
                ]);
                $this->hitungNilaiAkhir($d['mahasiswa_id'], $mataKuliah->id);
            }
        });
        return redirect()->route('nilai.index',$mataKuliah->id)->with('success','Nilai teori berhasil disimpan.');
    }

    public function exportTeoriExcel(MataKuliah $mataKuliah)
    {
        abort_unless($mataKuliah->hasTeori(), 403, 'Mata kuliah ini tidak memiliki komponen teori.');

        return Excel::download(
            new NilaiTeoriExport($mataKuliah),
            'Nilai-Teori-' . $mataKuliah->kode . '.xlsx'
        );
    }

    public function formKeaktifan(MataKuliah $mataKuliah) {
        abort_unless($mataKuliah->hasTeori(), 403, 'Mata kuliah ini tidak memiliki komponen teori.');
        $mataKuliah->load(['kampus','kelas','mahasiswa']);
        $keaktifanData = NilaiKeaktifanDetail::where('mata_kuliah_id',$mataKuliah->id)->get()->groupBy('mahasiswa_id');
        
        // Ambil data absensi mahasiswa pada mata kuliah ini
        $absensiData = Absensi::where('mata_kuliah_id', $mataKuliah->id)
            ->get()
            ->keyBy(fn($a) => "{$a->mahasiswa_id}_{$a->pertemuan_ke}");

        return view('nilai.form-keaktifan', compact('mataKuliah','keaktifanData', 'absensiData'));
    }

    public function simpanKeaktifan(Request $request, MataKuliah $mataKuliah) {
        $request->validate([
            'nilai' => 'required|array',
            'nilai.*.mahasiswa_id' => 'required|exists:mahasiswa,id',
            'nilai.*.pertemuan' => 'array',
        ]);

        DB::transaction(function() use ($request, $mataKuliah) {
            $jumlahPertemuan = $mataKuliah->total_pertemuan > 0 ? $mataKuliah->total_pertemuan : 14;
            foreach ($request->nilai as $d) {
                $mahasiswaId = $d['mahasiswa_id'];
                $totalSkor = 0;

                if (isset($d['pertemuan'])) {
                    foreach ($d['pertemuan'] as $ke => $data) {
                        $skor = $data['skor'] ?? null;
                        $indikator = $data['indikator'] ?? null;

                        if ($skor !== null && $skor !== '') {
                            $skorVal = floatval($skor);
                            if ($skorVal > 100) $skorVal = 100; // Cap at 100
                            
                            $indikatorArray = json_decode($indikator, true);

                            NilaiKeaktifanDetail::updateOrCreate(
                                ['mahasiswa_id' => $mahasiswaId, 'mata_kuliah_id' => $mataKuliah->id, 'pertemuan_ke' => $ke],
                                ['skor' => $skorVal, 'indikator' => $indikatorArray]
                            );
                            $totalSkor += $skorVal;
                        } else {
                            NilaiKeaktifanDetail::where('mahasiswa_id', $mahasiswaId)
                                ->where('mata_kuliah_id', $mataKuliah->id)
                                ->where('pertemuan_ke', $ke)
                                ->delete();
                        }
                    }
                }

                $rataKeaktifan = $totalSkor / $jumlahPertemuan;
                $nt = NilaiTeori::firstOrNew(['mahasiswa_id' => $mahasiswaId, 'mata_kuliah_id' => $mataKuliah->id]);
                $nt->keaktifan = $rataKeaktifan;
                $nt->nilai_akhir_teori = $nt->hitung();
                $nt->save();

                $this->hitungNilaiAkhir($mahasiswaId, $mataKuliah->id);
            }
        });
        return redirect()->route('nilai.index',$mataKuliah->id)->with('success','Nilai keaktifan detail berhasil disimpan.');
    }

    public function formTugas(MataKuliah $mataKuliah) {
        abort_unless($mataKuliah->hasTeori(), 403, 'Mata kuliah ini tidak memiliki komponen teori.');
        $mataKuliah->load(['kampus','kelas','mahasiswa']);
        $tugasData = NilaiTugasDetail::where('mata_kuliah_id',$mataKuliah->id)->get()->groupBy('mahasiswa_id');
        
        // Ambil semua nama tugas unik untuk kolom tabel
        $semuaTugas = NilaiTugasDetail::where('mata_kuliah_id', $mataKuliah->id)->select('nama_tugas')->distinct()->pluck('nama_tugas')->toArray();
        if (empty($semuaTugas)) {
            $semuaTugas = ['Tugas 1']; // default kalau kosong
        }

        return view('nilai.form-tugas', compact('mataKuliah','tugasData','semuaTugas'));
    }

    public function simpanTugas(Request $request, MataKuliah $mataKuliah) {
        $request->validate([
            'nilai' => 'required|array',
            'nilai.*.mahasiswa_id' => 'required|exists:mahasiswa,id',
            'nilai.*.tugas' => 'array',
        ]);

        DB::transaction(function() use ($request, $mataKuliah) {
            // Collect all submitted task names
            $submittedTugas = [];
            foreach ($request->nilai as $d) {
                if (isset($d['tugas'])) {
                    foreach (array_keys($d['tugas']) as $namaTugas) {
                        $submittedTugas[$namaTugas] = true;
                    }
                }
            }
            $submittedTugasList = array_keys($submittedTugas);

            // Delete tasks that are removed from the form
            if (empty($submittedTugasList)) {
                NilaiTugasDetail::where('mata_kuliah_id', $mataKuliah->id)->delete();
            } else {
                NilaiTugasDetail::where('mata_kuliah_id', $mataKuliah->id)
                    ->whereNotIn('nama_tugas', $submittedTugasList)
                    ->delete();
            }

            foreach ($request->nilai as $d) {
                $mahasiswaId = $d['mahasiswa_id'];
                $totalSkor = 0;
                $jumlahTugas = 0;

                if (isset($d['tugas'])) {
                    foreach ($d['tugas'] as $namaTugas => $skor) {
                        if ($skor !== null && $skor !== '') {
                            NilaiTugasDetail::updateOrCreate(
                                ['mahasiswa_id' => $mahasiswaId, 'mata_kuliah_id' => $mataKuliah->id, 'nama_tugas' => $namaTugas],
                                ['skor' => $skor]
                            );
                        } else {
                            NilaiTugasDetail::where('mahasiswa_id', $mahasiswaId)
                                ->where('mata_kuliah_id', $mataKuliah->id)
                                ->where('nama_tugas', $namaTugas)
                                ->delete();
                        }
                    }
                }
                
                // Get fresh state in case there are other tasks not in this request (though UI sends all)
                $semuaTugasMhs = NilaiTugasDetail::where('mahasiswa_id', $mahasiswaId)->where('mata_kuliah_id', $mataKuliah->id)->get();
                $jumlahTugasDb = $semuaTugasMhs->count();
                $totalSkorDb = $semuaTugasMhs->sum('skor');

                $rataTugas = $jumlahTugasDb > 0 ? ($totalSkorDb / $jumlahTugasDb) : 0;

                $nt = NilaiTeori::firstOrNew(['mahasiswa_id' => $mahasiswaId, 'mata_kuliah_id' => $mataKuliah->id]);
                $nt->tugas = $rataTugas;
                $nt->nilai_akhir_teori = $nt->hitung();
                $nt->save();

                $this->hitungNilaiAkhir($mahasiswaId, $mataKuliah->id);
            }
        });
        return redirect()->route('nilai.index',$mataKuliah->id)->with('success','Nilai tugas detail berhasil disimpan.');
    }

    /**
     * Menghitung & menyimpan nilai akhir via PenilaianService.
     */
    public function hitungNilaiAkhir(int $mahasiswaId, int $mkId): NilaiAkhir {
        return PenilaianService::prosesNilaiMahasiswa($mahasiswaId, $mkId);
    }

    /**
     * Kalkulasi ulang seluruh nilai mahasiswa pada sebuah mata kuliah.
     */
    public function kalkulasiUlang(MataKuliah $mataKuliah) {
        PenilaianService::kalkulasiUlangMataKuliah($mataKuliah->id);
        return redirect()->route('nilai.index', $mataKuliah->id)->with('success', 'Seluruh nilai berhasil dikalkulasi ulang sesuai aturan penilaian terbaru.');
    }
}
