<?php

namespace App\Http\Controllers;

use App\Models\BobotNilai;
use Illuminate\Http\Request;

class BobotNilaiController extends Controller
{
    public function index()
    {
        $bobot = BobotNilai::first();

        return view('bobot.index', compact('bobot'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'kehadiran' => 'required|numeric|min:0|max:100',
            'tugas'     => 'required|numeric|min:0|max:100',
            'uts'       => 'required|numeric|min:0|max:100',
            'uas'       => 'required|numeric|min:0|max:100',
        ], [
            'required' => ':attribute wajib diisi.',
            'numeric'  => ':attribute harus berupa angka.',
            'min'      => ':attribute minimal :min.',
            'max'      => ':attribute maksimal :max.',
        ], [
            'kehadiran' => 'Kehadiran',
            'tugas'     => 'Tugas',
            'uts'       => 'UTS',
            'uas'       => 'UAS',
        ]);

        $total = $request->kehadiran + $request->tugas + $request->uts + $request->uas;

        if ($total != 100) {
            return back()->withErrors('Total bobot harus 100%');
        }

        BobotNilai::updateOrCreate(
            ['id' => 1],
            [
                'kehadiran' => $request->kehadiran,
                'tugas'     => $request->tugas,
                'uts'       => $request->uts,
                'uas'       => $request->uas,
                'keaktifan' => 0,
                'akhlak'    => 0,
            ]
        );

        return back()->with('success','Bobot berhasil diperbarui');
    }
}