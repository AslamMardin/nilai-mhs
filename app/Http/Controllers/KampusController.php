<?php

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
        $request->validate(['nama' => 'required|max:150', 'kode' => 'required|unique:kampus,kode|max:20', 'alamat' => 'nullable', 'telepon' => 'nullable|max:20']);
        Kampus::create($request->only('nama', 'kode', 'alamat', 'telepon'));
        return redirect()->route('kampus.index')->with('success', 'Kampus berhasil ditambahkan.');
    }
    public function edit(Kampus $kampus)
    {
       
        return view('kampus.edit', compact('kampus'));
    }
    public function update(Request $request, Kampus $kampus)
    {
        $request->validate(['nama' => 'required|max:150', 'kode' => "required|unique:kampus,kode,{$kampus->id}|max:20", 'alamat' => 'nullable', 'telepon' => 'nullable|max:20']);
        $kampus->update($request->only('nama', 'kode', 'alamat', 'telepon'));
        return redirect()->route('kampus.index')->with('success', 'Kampus diperbarui.');
    }
    public function destroy(Kampus $kampus)
    {
      
        $kampus->delete();
        return redirect()->route('kampus.index')->with('success', 'Kampus dihapus.');
    }
}
