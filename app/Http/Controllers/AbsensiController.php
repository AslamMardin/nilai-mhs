<?php namespace App\Http\Controllers;
use App\Models\Absensi;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller {
    public function pilih() {
        $kampusId = session('kampus_id') ?? Auth::user()->kampus_id;
        $mataKuliahList = MataKuliah::with(['kelas'])
            ->where('kampus_id', $kampusId)
            ->withCount('mahasiswa')
            ->with(['absensi'=>fn($q)=>$q->select('mata_kuliah_id','pertemuan_ke')])
            ->get();
        return view('absensi.pilih', compact('mataKuliahList'));
    }

    public function index(MataKuliah $mataKuliah) {
        $mataKuliah->load(['kampus','kelas','mahasiswa']);
        $existingAbsensi = Absensi::where('mata_kuliah_id',$mataKuliah->id)
            ->get()->keyBy(fn($a)=>"{$a->mahasiswa_id}_{$a->pertemuan_ke}");

        $rekapKehadiran = $mataKuliah->mahasiswa->mapWithKeys(fn($mhs) => [
            $mhs->id => [
                'poin'  => Absensi::hitungPoin($mhs->id, $mataKuliah->id),
                'persen'=> Absensi::hitungPersen($mhs->id, $mataKuliah->id, $mataKuliah->total_pertemuan),
                'lolos' => Absensi::hitungPersen($mhs->id, $mataKuliah->id, $mataKuliah->total_pertemuan) >= 75,
            ]
        ]);
        $tanggalPertemuan = Absensi::where('mata_kuliah_id', $mataKuliah->id)
    ->select('pertemuan_ke', 'tanggal')
    ->distinct()
    ->orderBy('pertemuan_ke')
    ->pluck('tanggal', 'pertemuan_ke');

        return view('absensi.index', compact('mataKuliah','existingAbsensi','rekapKehadiran', 'tanggalPertemuan'));
    }

    public function simpan(Request $request, MataKuliah $mataKuliah) {
        $request->validate([
            'pertemuan_ke'           => 'required|integer|min:1|max:16',
            'tanggal'                => 'required|date',
            'absensi'                => 'required|array',
            'absensi.*.mahasiswa_id' => 'required|exists:mahasiswa,id',
            'absensi.*.status'       => 'required|in:H,T,S,I,A',
        ]);
        DB::transaction(function() use ($request, $mataKuliah) {
            foreach ($request->absensi as $d) {
                Absensi::updateOrCreate(
                    ['mahasiswa_id'=>$d['mahasiswa_id'],'mata_kuliah_id'=>$mataKuliah->id,'pertemuan_ke'=>$request->pertemuan_ke],
                    ['tanggal'=>$request->tanggal,'status'=>$d['status'],'keterangan'=>$d['keterangan']??null]
                );
            }
        });
        return redirect()->route('absensi.index', ['mataKuliah' => $mataKuliah->id, 'pertemuan' => $request->pertemuan_ke])
            ->with('success',"Absensi pertemuan ke-{$request->pertemuan_ke} berhasil disimpan.");
    }

   public function rekap(MataKuliah $mataKuliah)
{
    $mataKuliah->load(['kampus','kelas','mahasiswa']);

    // 🔥 Ambil semua absensi SEKALI (hindari query berulang)
    $allAbsensi = Absensi::where('mata_kuliah_id', $mataKuliah->id)
        ->get()
        ->groupBy('mahasiswa_id');

    // 🔥 Ambil tanggal per pertemuan
    $tanggalPertemuan = Absensi::where('mata_kuliah_id', $mataKuliah->id)
        ->select('pertemuan_ke', 'tanggal')
        ->distinct()
        ->orderBy('pertemuan_ke')
        ->pluck('tanggal', 'pertemuan_ke');

    // 🔥 Rekap data mahasiswa
    $rekap = $mataKuliah->mahasiswa->map(function($mhs) use ($mataKuliah, $allAbsensi) {

        $absensiList = ($allAbsensi[$mhs->id] ?? collect())
            ->keyBy('pertemuan_ke');

        $poin = $absensiList->sum(fn($a) => Absensi::BOBOT[$a->status] ?? 0);

        $persen = $mataKuliah->total_pertemuan > 0
            ? round(($poin / ($mataKuliah->total_pertemuan * 2)) * 100, 1)
            : 0;

        $hitung = collect(array_keys(Absensi::LABEL))
            ->mapWithKeys(fn($s) => [
                $s => $absensiList->where('status', $s)->count()
            ])
            ->toArray();

        return [
            'mahasiswa' => $mhs,
            'absensi'   => $absensiList,
            'poin'      => $poin,
            'persen'    => $persen,
            'lolos'     => $persen >= 75,
            'hitung'    => $hitung,
        ];
    });

    return view('absensi.rekap', compact(
        'mataKuliah',
        'rekap',
        'tanggalPertemuan' // 🔥 kirim ke view
    ));
}
}
