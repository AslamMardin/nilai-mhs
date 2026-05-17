<?php namespace App\Http\Controllers;
use App\Exports\NilaiTeoriExport;
use App\Models\{Absensi,MataKuliah,NilaiTeori,NilaiPraktikum,NilaiAkhir};
use App\Models\BobotNilai;
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
            'nilaiPraktikum' => fn($q)=>$q->where('mata_kuliah_id',$mataKuliah->id),
            'nilaiAkhir'     => fn($q)=>$q->where('mata_kuliah_id',$mataKuliah->id),
        ])->get();
        return view('nilai.index', compact('mataKuliah','mahasiswaList', 'bobot'));
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

    public function formPraktikum(MataKuliah $mataKuliah) {
        abort_unless($mataKuliah->hasPraktikum(), 403);
        $mataKuliah->load(['kampus','kelas','mahasiswa']);
        $nilaiPrakData = NilaiPraktikum::where('mata_kuliah_id',$mataKuliah->id)->get()->keyBy('mahasiswa_id');
        return view('nilai.form-praktikum', compact('mataKuliah','nilaiPrakData'));
    }

    public function simpanPraktikum(Request $request, MataKuliah $mataKuliah) {
        $request->validate(['nilai'=>'required|array','nilai.*.mahasiswa_id'=>'required|exists:mahasiswa,id','nilai.*.nilai_praktikum'=>'required|numeric|min:0|max:100']);
        DB::transaction(function() use ($request, $mataKuliah) {
            foreach ($request->nilai as $d) {
                NilaiPraktikum::updateOrCreate(['mahasiswa_id'=>$d['mahasiswa_id'],'mata_kuliah_id'=>$mataKuliah->id],['nilai_praktikum'=>$d['nilai_praktikum']]);
                $this->hitungNilaiAkhir($d['mahasiswa_id'], $mataKuliah->id);
            }
        });
        return redirect()->route('nilai.index',$mataKuliah->id)->with('success','Nilai praktikum berhasil disimpan.');
    }

    /**
     * Menghitung & menyimpan nilai akhir:
     * - Teori:           NA = NA_Teori
     * - Praktikum:       NA = Nilai_Praktikum
     * - Teori+Praktikum: NA = (NA_Teori * 0.5) + (Nilai_Praktikum * 0.5)
     * Syarat lulus: kehadiran >= 75% DAN nilai_akhir >= 55
     */
    public function hitungNilaiAkhir(int $mahasiswaId, int $mkId): NilaiAkhir {
        $mk  = MataKuliah::find($mkId);
        $nt  = NilaiTeori::where('mahasiswa_id',$mahasiswaId)->where('mata_kuliah_id',$mkId)->first();
        $np  = NilaiPraktikum::where('mahasiswa_id',$mahasiswaId)->where('mata_kuliah_id',$mkId)->first();
        $naT = $nt?->nilai_akhir_teori ?? 0;
        $naP = $np?->nilai_praktikum ?? 0;

        $nilaiAkhir = match($mk->jenis) {
            'teori'           => $naT,
            'praktikum'       => $naP,
            'teori_praktikum' => round(($naT * 0.5) + ($naP * 0.5), 2),
            default           => $naT,
        };

        $poin   = Absensi::hitungPoin($mahasiswaId, $mkId);
        $persen = Absensi::hitungPersen($mahasiswaId, $mkId, $mk->total_pertemuan);
        $lolos  = $persen >= 75.0;

        $keteranganGagal = null;
        if (!$lolos) {
            $status = 'tidak_lulus';
            $keteranganGagal = "Kehadiran {$persen}% (min 75%)";
        } elseif ($nilaiAkhir < 55) {
            $status = 'tidak_lulus';
            $keteranganGagal = "Nilai akhir {$nilaiAkhir} (min 55)";
        } else {
            $status = 'lulus';
        }

        $record = NilaiAkhir::updateOrCreate(
            ['mahasiswa_id'=>$mahasiswaId,'mata_kuliah_id'=>$mkId],
            ['nilai_teori'=>$naT,'nilai_praktikum'=>$naP,'nilai_akhir'=>$nilaiAkhir,'huruf_mutu'=>NilaiAkhir::toHuruf($nilaiAkhir),'persentase_kehadiran'=>$persen,'poin_kehadiran'=>$poin,'status_kelulusan'=>$status,'keterangan_gagal'=>$keteranganGagal]
        );
        DB::table('pendaftaran_mahasiswa')->where('mahasiswa_id',$mahasiswaId)->where('mata_kuliah_id',$mkId)->update(['status'=>$status==='lulus'?'lulus':'tidak_lulus']);
        return $record;
    }
}
