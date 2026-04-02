<?php

namespace App\Http\Controllers;

use App\Exports\MahasiswaExport;
use App\Exports\MahasiswaTemplateExport;
use App\Imports\MahasiswaImport;
use App\Models\Kampus;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $kampusId  = session('kampus_id') ?? Auth::user()->kampus_id;
        $mahasiswa = Mahasiswa::with(['kampus', 'kelas'])
            ->when($request->kampus_id ?? $kampusId, fn($q, $k) => $q->where('kampus_id', $k))
            ->when($request->kelas_id, fn($q, $k) => $q->where('kelas_id', $k))
            ->when($request->search, fn($q, $s) => $q->where(fn($q2) => $q2->where('nama', 'like', "%$s%")->orWhere('nim', 'like', "%$s%")))
            ->orderBy('nim')->paginate(20)->withQueryString();
        $kampusList = Kampus::all();
        $kelasList  = Kelas::when($request->kampus_id ?? $kampusId, fn($q, $k) => $q->where('kampus_id', $k))->get();
        return view('mahasiswa.index', compact('mahasiswa', 'kampusList', 'kelasList')
            + ['kampusId' => $request->kampus_id ?? $kampusId, 'kelasId' => $request->kelas_id, 'search' => $request->search]);
    }
    public function create()
    {
        return view('mahasiswa.create', ['kampusList' => Kampus::all(), 'kelasList' => Kelas::all()]);
    }
    public function store(Request $request)
    {
        $request->validate(['kampus_id' => 'required|exists:kampus,id', 'kelas_id' => 'required|exists:kelas,id', 'nim' => 'required|unique:mahasiswa,nim|max:20', 'nama' => 'required|max:100', 'jenis_kelamin' => 'required|in:L,P', 'email' => 'nullable|email|unique:mahasiswa,email', 'telepon' => 'nullable|max:20', 'status' => 'required|in:aktif,cuti,lulus,dropout', 'tanggal_lahir' => 'nullable|date', 'tempat_lahir' => 'nullable|max:50', 'alamat' => 'nullable']);
        Mahasiswa::create($request->all());
        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan.');
    }
    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load(['kampus', 'kelas', 'mataKuliah', 'nilaiAkhir.mataKuliah']);
        return view('mahasiswa.show', compact('mahasiswa'));
    }
    public function edit(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.edit', ['mahasiswa' => $mahasiswa, 'kampusList' => Kampus::all(), 'kelasList' => Kelas::where('kampus_id', $mahasiswa->kampus_id)->get()]);
    }
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate(['kampus_id' => 'required|exists:kampus,id', 'kelas_id' => 'required|exists:kelas,id', 'nim' => "required|unique:mahasiswa,nim,{$mahasiswa->id}|max:20", 'nama' => 'required|max:100', 'jenis_kelamin' => 'required|in:L,P', 'email' => "nullable|email|unique:mahasiswa,email,{$mahasiswa->id}", 'status' => 'required|in:aktif,cuti,lulus,dropout']);
        $mahasiswa->update($request->all());
        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa diperbarui.');
    }
    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();
        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa dihapus.');
    }
    public function formDaftar(Mahasiswa $mahasiswa)
    {
        $mataKuliah = MataKuliah::where('kampus_id', $mahasiswa->kampus_id)->with('kelas')->get();
        $terdaftar  = $mahasiswa->mataKuliah->pluck('id')->toArray();
        return view('mahasiswa.daftar-matkul', compact('mahasiswa', 'mataKuliah', 'terdaftar'));
    }
    public function simpanDaftar(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate(['mata_kuliah_ids' => 'nullable|array', 'mata_kuliah_ids.*' => 'exists:mata_kuliah,id', 'tahun_ajaran' => 'required|integer', 'semester' => 'required|in:ganjil,genap']);
        $ids   = $request->mata_kuliah_ids ?? [];
        $pivot = collect($ids)->mapWithKeys(fn($id) => [$id => ['tahun_ajaran' => $request->tahun_ajaran, 'semester' => $request->semester, 'status' => 'aktif']])->toArray();
        $mahasiswa->mataKuliah()->sync($pivot);
        return redirect()->route('mahasiswa.show', $mahasiswa->id)->with('success', 'Pendaftaran diperbarui.');
    }

    public function formImport()
    {
        
        return view('mahasiswa.import');
    }

    public function downloadTemplate()
    {
        return Excel::download(new MahasiswaExport, 'data_mahasiswa.xlsx');
    }



public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

  

        Excel::import(
            new MahasiswaImport(session('kampus_id')),
            $request->file('file')
        );

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Import berhasil');

   
}
}
