<?php

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
