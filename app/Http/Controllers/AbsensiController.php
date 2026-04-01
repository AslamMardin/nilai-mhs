<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    // ─────────────────────────────────────────────────────
    // TAMPIL FORM ABSENSI PER MATA KULIAH
    // ─────────────────────────────────────────────────────

    public function index(int $mataKuliahId)
    {
        $mataKuliah = MataKuliah::with(['kampus', 'kelas', 'mahasiswa'])->findOrFail($mataKuliahId);

        // Ambil absensi yang sudah ada, dikelompokkan per pertemuan per mahasiswa
        $existingAbsensi = Absensi::where('mata_kuliah_id', $mataKuliahId)
            ->get()
            ->groupBy(fn($a) => "{$a->mahasiswa_id}_{$a->pertemuan_ke}");

        // Rekap kehadiran per mahasiswa
        $rekapKehadiran = $mataKuliah->mahasiswa->mapWithKeys(function ($mhs) use ($mataKuliahId, $mataKuliah) {
            $poin       = Absensi::hitungPoinKehadiran($mhs->id, $mataKuliahId);
            $persentase = Absensi::hitungPersentaseKehadiran($mhs->id, $mataKuliahId, $mataKuliah->total_pertemuan);

            return [$mhs->id => [
                'poin'       => $poin,
                'persentase' => $persentase,
                'lolos'      => $persentase >= 75,
            ]];
        });

        return view('absensi.index', compact(
            'mataKuliah',
            'existingAbsensi',
            'rekapKehadiran'
        ));
    }

    // ─────────────────────────────────────────────────────
    // SIMPAN/UPDATE ABSENSI (BULK PER PERTEMUAN)
    // ─────────────────────────────────────────────────────

    public function simpan(Request $request, int $mataKuliahId)
    {
        $request->validate([
            'pertemuan_ke'           => 'required|integer|min:1|max:16',
            'tanggal'                => 'required|date',
            'absensi'                => 'required|array',
            'absensi.*.mahasiswa_id' => 'required|exists:mahasiswa,id',
            'absensi.*.status'       => 'required|in:H,T,S,I,A',
            'absensi.*.keterangan'   => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $mataKuliahId) {
            foreach ($request->absensi as $data) {
                Absensi::updateOrCreate(
                    [
                        'mahasiswa_id'   => $data['mahasiswa_id'],
                        'mata_kuliah_id' => $mataKuliahId,
                        'pertemuan_ke'   => $request->pertemuan_ke,
                    ],
                    [
                        'tanggal'    => $request->tanggal,
                        'status'     => $data['status'],
                        'keterangan' => $data['keterangan'] ?? null,
                    ]
                );
            }
        });

        return redirect()
            ->route('absensi.index', $mataKuliahId)
            ->with('success', "Absensi pertemuan ke-{$request->pertemuan_ke} berhasil disimpan.");
    }

    // ─────────────────────────────────────────────────────
    // REKAP KEHADIRAN PER MAHASISWA PER MATKUL
    // ─────────────────────────────────────────────────────

    public function rekap(int $mataKuliahId)
    {
        $mataKuliah = MataKuliah::with(['kampus', 'kelas'])->findOrFail($mataKuliahId);

        $rekap = $mataKuliah->mahasiswa->map(function ($mhs) use ($mataKuliahId, $mataKuliah) {
            $absensiList = Absensi::where('mahasiswa_id', $mhs->id)
                ->where('mata_kuliah_id', $mataKuliahId)
                ->orderBy('pertemuan_ke')
                ->get()
                ->keyBy('pertemuan_ke');

            $poin       = $absensiList->sum(fn($a) => Absensi::BOBOT_KEHADIRAN[$a->status] ?? 0);
            $persentase = Absensi::hitungPersentaseKehadiran($mhs->id, $mataKuliahId, $mataKuliah->total_pertemuan);

            // Hitung per-status
            $hitungStatus = collect(Absensi::LABEL_STATUS)->keys()->mapWithKeys(
                fn($s) => [$s => $absensiList->where('status', $s)->count()]
            );

            return [
                'mahasiswa'    => $mhs,
                'absensi'      => $absensiList,
                'poin'         => $poin,
                'persentase'   => $persentase,
                'lolos'        => $persentase >= 75,
                'hitung_status' => $hitungStatus,
            ];
        });

        return view('absensi.rekap', compact('mataKuliah', 'rekap'));
    }
}
