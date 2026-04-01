<?php

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
