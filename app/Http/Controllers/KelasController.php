<?php namespace App\Http\Controllers;
use App\Models\Kelas;
use App\Models\Kampus;
use Illuminate\Http\Request;

class KelasController extends Controller {
    public function index() {
        $kelasList  = Kelas::with('kampus')->withCount(['mahasiswa','mataKuliah'])->get()->groupBy('kampus_id');
        $kampusList = Kampus::all();
        return view('kelas.index', compact('kelasList','kampusList'));
    }
    public function create() {
        $kampusList = Kampus::all();
        return view('kelas.create', compact('kampusList'));
    }
    public function store(Request $request) {
        $request->validate(['kampus_id'=>'required|exists:kampus,id','nama'=>'required|max:80','kode'=>'required|unique:kelas,kode|max:20','semester'=>'required|in:ganjil,genap','tahun_ajaran'=>'required|integer|min:2000|max:2099','wali_kelas'=>'nullable|max:100']);
        Kelas::create($request->only('kampus_id','nama','kode','semester','tahun_ajaran','wali_kelas'));
        return redirect()->route('kelas.index')->with('success','Kelas berhasil ditambahkan.');
    }
    public function show(Kelas $kelas) {
        $kelas->load(['kampus','mahasiswa','mataKuliah']);
        return view('kelas.show', compact('kelas'));
    }
    public function edit(Kelas $kelas) {
        $kampusList = Kampus::all();
        return view('kelas.edit', compact('kelas','kampusList'));
    }
    public function update(Request $request, Kelas $kelas) {
        $request->validate(['kampus_id'=>'required|exists:kampus,id','nama'=>'required|max:80','kode'=>"required|unique:kelas,kode,{$kelas->id}|max:20",'semester'=>'required|in:ganjil,genap','tahun_ajaran'=>'required|integer|min:2000|max:2099','wali_kelas'=>'nullable|max:100']);
        $kelas->update($request->only('kampus_id','nama','kode','semester','tahun_ajaran','wali_kelas'));
        return redirect()->route('kelas.index')->with('success','Kelas diperbarui.');
    }
    public function destroy(Kelas $kelas) {
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success','Kelas dihapus.');
    }
}
