<?php

namespace App\Exports;

use App\Models\NilaiTeori;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class NilaiTeoriExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
{
    protected $mataKuliah;

    public function __construct($mataKuliah)
    {
        $this->mataKuliah = $mataKuliah;
    }

    public function collection()
    {
        $rows = [];
        $no = 1;

        $mahasiswa = $this->mataKuliah->mahasiswa()->orderBy('nama')->get();
        $nilaiTeori = NilaiTeori::where('mata_kuliah_id', $this->mataKuliah->id)
            ->get()->keyBy('mahasiswa_id');

        $totalPertemuan = $this->mataKuliah->total_pertemuan > 0 ? $this->mataKuliah->total_pertemuan : 16;
        foreach ($mahasiswa as $mhs) {
            $nilai = $nilaiTeori->get($mhs->id);
            $persenHadir = \App\Models\Absensi::hitungPersen($mhs->id, $this->mataKuliah->id, $totalPertemuan);
            $keaktifan = $nilai?->keaktifan ?? 0;
            $tugas = $nilai?->tugas ?? 0;
            $uts = $nilai?->uts ?? 0;
            $uas = $nilai?->uas ?? 0;
            $na = $nilai?->nilai_akhir_teori ?? 0;

            $rows[] = [
                'No' => $no++,
                'NIM' => $mhs->nim,
                'Nama' => $mhs->nama,
                'Kehadiran (%)' => $persenHadir,
                'Keaktifan' => $keaktifan,
                'Tugas' => $tugas,
                'UTS' => $uts,
                'UAS' => $uas,
                'NA Teori' => $na,
            ];
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return ['No', 'NIM', 'Nama', 'Kehadiran (%)', 'Keaktifan', 'Tugas', 'UTS', 'UAS', 'NA Teori'];
    }

    public function title(): string
    {
        $title = preg_replace('/[^a-zA-Z0-9_\s]/', '', $this->mataKuliah->kode ?? 'Teori');
        return substr('Teori ' . $title, 0, 31);
    }
}
