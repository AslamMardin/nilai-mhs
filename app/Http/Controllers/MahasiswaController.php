<?php
// ===================================================
// MahasiswaController
// ===================================================
namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Kampus;
use App\Models\Kelas;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $kampusId = $request->kampus_id;
        $kelasId  = $request->kelas_id;
        $search   = $request->search;

        $mahasiswa = Mahasiswa::with(['kampus', 'kelas'])
            ->when($kampusId, fn($q) => $q->where('kampus_id', $kampusId))
            ->when($kelasId,  fn($q) => $q->where('kelas_id', $kelasId))
            ->when($search,   fn($q) => $q->where(function ($q2) use ($search) {
                $q2->where('nama', 'like', "%{$search}%")
                   ->orWhere('nim', 'like', "%{$search}%");
            }))
            ->orderBy('nim')
            ->paginate(20)
            ->withQueryString();

        $kampusList = Kampus::all();
        $kelasList  = Kelas::when($kampusId, fn($q) => $q->where('kampus_id', $kampusId))->get();

        return view('mahasiswa.index', compact('mahasiswa', 'kampusList', 'kelasList', 'kampusId', 'kelasId', 'search'));
    }

    public function create()
    {
        $kampusList = Kampus::all();
        $kelasList  = Kelas::all();
        return view('mahasiswa.create', compact('kampusList', 'kelasList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kampus_id'     => 'required|exists:kampus,id',
            'kelas_id'      => 'required|exists:kelas,id',
            'nim'           => 'required|string|unique:mahasiswa,nim|max:20',
            'nama'          => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'email'         => 'nullable|email|unique:mahasiswa,email|max:100',
            'telepon'       => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir'  => 'nullable|string|max:50',
            'status'        => 'required|in:aktif,cuti,lulus,dropout',
        ]);

        Mahasiswa::create($data);

        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load(['kampus', 'kelas', 'mataKuliah', 'nilaiAkhir.mataKuliah']);
        return view('mahasiswa.show', compact('mahasiswa'));
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $kampusList = Kampus::all();
        $kelasList  = Kelas::where('kampus_id', $mahasiswa->kampus_id)->get();
        return view('mahasiswa.edit', compact('mahasiswa', 'kampusList', 'kelasList'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $data = $request->validate([
            'kampus_id'     => 'required|exists:kampus,id',
            'kelas_id'      => 'required|exists:kelas,id',
            'nim'           => "required|string|unique:mahasiswa,nim,{$mahasiswa->id}|max:20",
            'nama'          => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'email'         => "nullable|email|unique:mahasiswa,email,{$mahasiswa->id}|max:100",
            'telepon'       => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir'  => 'nullable|string|max:50',
            'status'        => 'required|in:aktif,cuti,lulus,dropout',
        ]);

        $mahasiswa->update($data);

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();
        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa dihapus.');
    }

    // Form pendaftaran ke mata kuliah
    public function formDaftar(Mahasiswa $mahasiswa)
    {
        $mataKuliah = MataKuliah::where('kampus_id', $mahasiswa->kampus_id)->get();
        $terdaftar  = $mahasiswa->mataKuliah->pluck('id')->toArray();
        return view('mahasiswa.daftar-matkul', compact('mahasiswa', 'mataKuliah', 'terdaftar'));
    }

    public function simpanDaftar(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'mata_kuliah_ids'   => 'required|array',
            'mata_kuliah_ids.*' => 'exists:mata_kuliah,id',
            'tahun_ajaran'      => 'required|integer',
            'semester'          => 'required|in:ganjil,genap',
        ]);

        // Sinkronisasi pendaftaran (tambah/hapus)
        $syncData = collect($request->mata_kuliah_ids)
            ->mapWithKeys(fn($id) => [$id => [
                'tahun_ajaran' => $request->tahun_ajaran,
                'semester'     => $request->semester,
                'status'       => 'aktif',
            ]])
            ->toArray();

        $mahasiswa->mataKuliah()->sync($syncData);

        return redirect()->route('mahasiswa.show', $mahasiswa->id)
            ->with('success', 'Pendaftaran mata kuliah berhasil diperbarui.');
    }
}

