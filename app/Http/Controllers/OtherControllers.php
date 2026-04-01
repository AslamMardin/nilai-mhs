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


// ===================================================
// KampusController
// ===================================================

namespace App\Http\Controllers;

use App\Models\Kampus;
use Illuminate\Http\Request;

class KampusController extends Controller
{
    public function index()
    {
        $kampusList = Kampus::withCount(['kelas', 'mahasiswa', 'mataKuliah'])->get();
        return view('kampus.index', compact('kampusList'));
    }

    public function create()
    {
        return view('kampus.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'    => 'required|string|max:100',
            'kode'    => 'required|string|unique:kampus,kode|max:20',
            'alamat'  => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
        ]);
        Kampus::create($data);
        return redirect()->route('kampus.index')->with('success', 'Kampus berhasil ditambahkan.');
    }

    public function edit(Kampus $kampus)
    {
        return view('kampus.edit', compact('kampus'));
    }

    public function update(Request $request, Kampus $kampus)
    {
        $data = $request->validate([
            'nama'    => 'required|string|max:100',
            'kode'    => "required|string|unique:kampus,kode,{$kampus->id}|max:20",
            'alamat'  => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
        ]);
        $kampus->update($data);
        return redirect()->route('kampus.index')->with('success', 'Data kampus diperbarui.');
    }

    public function destroy(Kampus $kampus)
    {
        $kampus->delete();
        return redirect()->route('kampus.index')->with('success', 'Kampus dihapus.');
    }
}


// ===================================================
// KelasController
// ===================================================

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Kampus;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelasList = Kelas::with('kampus')
            ->withCount(['mahasiswa', 'mataKuliah'])
            ->get()
            ->groupBy('kampus_id');
        $kampusList = Kampus::all();
        return view('kelas.index', compact('kelasList', 'kampusList'));
    }

    public function create()
    {
        $kampusList = Kampus::all();
        return view('kelas.create', compact('kampusList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kampus_id'    => 'required|exists:kampus,id',
            'nama'         => 'required|string|max:50',
            'kode'         => 'required|string|unique:kelas,kode|max:20',
            'semester'     => 'required|in:ganjil,genap',
            'tahun_ajaran' => 'required|integer|min:2000|max:2099',
            'wali_kelas'   => 'nullable|string|max:100',
        ]);
        Kelas::create($data);
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas)
    {
        $kampusList = Kampus::all();
        return view('kelas.edit', compact('kelas', 'kampusList'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $data = $request->validate([
            'kampus_id'    => 'required|exists:kampus,id',
            'nama'         => 'required|string|max:50',
            'kode'         => "required|string|unique:kelas,kode,{$kelas->id}|max:20",
            'semester'     => 'required|in:ganjil,genap',
            'tahun_ajaran' => 'required|integer|min:2000|max:2099',
            'wali_kelas'   => 'nullable|string|max:100',
        ]);
        $kelas->update($data);
        return redirect()->route('kelas.index')->with('success', 'Data kelas diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas dihapus.');
    }
}


// ===================================================
// MataKuliahController
// ===================================================

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Kampus;
use App\Models\Kelas;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index(Request $request)
    {
        $kampusId = $request->kampus_id;
        $mataKuliah = MataKuliah::with(['kampus', 'kelas'])
            ->withCount('mahasiswa')
            ->when($kampusId, fn($q) => $q->where('kampus_id', $kampusId))
            ->orderBy('kampus_id')
            ->get();
        $kampusList = Kampus::all();
        return view('matakuliah.index', compact('mataKuliah', 'kampusList', 'kampusId'));
    }

    public function create()
    {
        $kampusList = Kampus::all();
        $kelasList  = Kelas::all();
        return view('matakuliah.create', compact('kampusList', 'kelasList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kampus_id'       => 'required|exists:kampus,id',
            'kelas_id'        => 'required|exists:kelas,id',
            'kode'            => 'required|string|unique:mata_kuliah,kode|max:20',
            'nama'            => 'required|string|max:150',
            'sks'             => 'required|integer|min:1|max:6',
            'jenis'           => 'required|in:teori,praktikum,teori_praktikum',
            'dosen'           => 'nullable|string|max:100',
            'total_pertemuan' => 'required|integer|min:1|max:16',
        ]);
        MataKuliah::create($data);
        return redirect()->route('mata-kuliah.index')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function show(MataKuliah $mataKuliah)
    {
        $mataKuliah->load(['kampus', 'kelas', 'mahasiswa', 'nilaiAkhir']);
        return view('matakuliah.show', compact('mataKuliah'));
    }

    public function edit(MataKuliah $mataKuliah)
    {
        $kampusList = Kampus::all();
        $kelasList  = Kelas::where('kampus_id', $mataKuliah->kampus_id)->get();
        return view('matakuliah.edit', compact('mataKuliah', 'kampusList', 'kelasList'));
    }

    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $data = $request->validate([
            'kampus_id'       => 'required|exists:kampus,id',
            'kelas_id'        => 'required|exists:kelas,id',
            'kode'            => "required|string|unique:mata_kuliah,kode,{$mataKuliah->id}|max:20",
            'nama'            => 'required|string|max:150',
            'sks'             => 'required|integer|min:1|max:6',
            'jenis'           => 'required|in:teori,praktikum,teori_praktikum',
            'dosen'           => 'nullable|string|max:100',
            'total_pertemuan' => 'required|integer|min:1|max:16',
        ]);
        $mataKuliah->update($data);
        return redirect()->route('mata-kuliah.index')->with('success', 'Mata kuliah diperbarui.');
    }

    public function destroy(MataKuliah $mataKuliah)
    {
        $mataKuliah->delete();
        return redirect()->route('mata-kuliah.index')->with('success', 'Mata kuliah dihapus.');
    }
}
