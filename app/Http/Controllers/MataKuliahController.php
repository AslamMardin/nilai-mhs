<?php namespace App\Http\Controllers;
use App\Models\MataKuliah;
use App\Models\Kampus;
use App\Models\Kelas;
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
}
