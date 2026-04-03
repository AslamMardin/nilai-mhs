<?php namespace App\Http\Controllers;
use App\Models\Kampus;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MataKuliahController extends Controller {
    public function index(Request $request) {
        $kampusId     = session('kampus_id') ?? Auth::user()->kampus_id;
        $filterKampus = $request->kampus_id ?? $kampusId;
        $filterKelas  = $request->kelas_id;
        $mataKuliah   = MataKuliah::with(['kampus','kelas'])
            ->withCount('mahasiswa')
            ->when($filterKampus, fn($q)=>$q->where('kampus_id',$filterKampus))
            ->when($filterKelas,  fn($q)=>$q->where('kelas_id',$filterKelas))
            ->get();
        $kampusList = Kampus::all();
        $kelasList  = Kelas::when($filterKampus, fn($q)=>$q->where('kampus_id',$filterKampus))->get();
        return view('matakuliah.index', compact('mataKuliah','kampusList','kelasList','filterKampus','filterKelas'));
    }
    public function create() {
        return view('matakuliah.create', [
            'kampusList' => Kampus::all(),
            'kelasList'  => Kelas::all(),
        ]);
    }
    public function store(Request $request) {
        $request->validate([
            'kampus_id'       => 'required|exists:kampus,id',
            'kelas_id'        => 'required|exists:kelas,id',
            'kode'            => 'required|unique:mata_kuliah,kode|max:20',
            'nama'            => 'required|max:150',
            'sks'             => 'required|integer|min:1|max:6',
            'jenis'           => 'required|in:teori,praktikum,teori_praktikum',
            'total_pertemuan' => 'required|integer|min:1|max:16',
            'dosen'           => 'nullable|max:100',
        ]);
        MataKuliah::create($request->only('kampus_id','kelas_id','kode','nama','sks','jenis','total_pertemuan','dosen'));
        return redirect()->route('matakuliah.index')->with('success','Mata kuliah berhasil ditambahkan.');
    }
    public function edit(MataKuliah $matakuliah) {
        return view('matakuliah.edit', [
            'mataKuliah' => $matakuliah,
            'kampusList' => Kampus::all(),
            'kelasList'  => Kelas::all(),
        ]);
    }
    public function update(Request $request, MataKuliah $matakuliah) {
        $request->validate([
            'kampus_id'       => 'required|exists:kampus,id',
            'kelas_id'        => 'required|exists:kelas,id',
            'kode'            => "required|unique:mata_kuliah,kode,{$matakuliah->id}|max:20",
            'nama'            => 'required|max:150',
            'sks'             => 'required|integer|min:1|max:6',
            'jenis'           => 'required|in:teori,praktikum,teori_praktikum',
            'total_pertemuan' => 'required|integer|min:1|max:16',
            'dosen'           => 'nullable|max:100',
        ]);
        $matakuliah->update($request->only('kampus_id','kelas_id','kode','nama','sks','jenis','total_pertemuan','dosen'));
        return redirect()->route('matakuliah.index')->with('success','Mata kuliah diperbarui.');
    }
    public function destroy(MataKuliah $matakuliah) {
        $matakuliah->delete();
        return redirect()->route('matakuliah.index')->with('success','Mata kuliah dihapus.');
    }

    public function daftar(Request $request, $id)
{
   
    $mk = MataKuliah::findOrFail($id);

    $mahasiswa = Mahasiswa::with(['kampus','kelas'])
        ->when($request->search, fn($q,$s)=>
            $q->where('nama','like',"%$s%")
              ->orWhere('nim','like',"%$s%")
        )
        ->when($request->kelas_id, fn($q,$k)=>$q->where('kelas_id',$k))
        ->paginate(100)->withQueryString();

    $kelasList  = Kampus::withCount('kelas')->find(session('kampus_id'));

    return view('matakuliah.daftar', compact('mk','mahasiswa','kelasList'));
}

public function storeDaftar(Request $request, $id)
{
    $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'exists:mahasiswa,id',
        'tahun_ajaran' => 'required|integer',
        'semester' => 'required|in:ganjil,genap',
    ]);

    $mk = MataKuliah::findOrFail($id);

    foreach ($request->ids as $mhsId) {

        $mahasiswa = Mahasiswa::find($mhsId);

        $mahasiswa->mataKuliah()->syncWithoutDetaching([
            $mk->id => [
                'tahun_ajaran' => $request->tahun_ajaran,
                'semester' => $request->semester,
                'status' => 'aktif',
            ]
        ]);
    }

    return redirect()->route('matakuliah.index')->with('success',
    count($request->ids) . " mahasiswa berhasil didaftarkan ke mata kuliah {$mk->nama} ({$request->semester} {$request->tahun_ajaran})"
);
}

public function peserta(Request $request, $id)
{
    $mk = MataKuliah::findOrFail($id);

    $mahasiswa = $mk->mahasiswa()
        ->when($request->search, fn($q) =>
            $q->where('nama', 'like', "%{$request->search}%")
              ->orWhere('nim', 'like', "%{$request->search}%")
        )
        ->when($request->kelas_id, fn($q) =>
            $q->where('kelas_id', $request->kelas_id)
        )
        ->paginate(30)
        ->withQueryString();

    $kelasList = Kampus::withCount('kelas')->find(session('kampus_id'));

    return view('matakuliah.peserta', compact('mk','mahasiswa','kelasList',));
}

public function removeMahasiswa(Request $request)
{
    $request->validate([
        'mahasiswa_id' => 'required',
        'mata_kuliah_id' => 'required',
    ]);

    $mhs = Mahasiswa::findOrFail($request->mahasiswa_id);

    $mhs->mataKuliah()->detach($request->mata_kuliah_id);

    return back()->with('success', 'Mahasiswa berhasil dihapus dari mata kuliah');
}
}
