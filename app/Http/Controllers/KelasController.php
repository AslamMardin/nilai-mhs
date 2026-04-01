<?php


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

