<?php

namespace App\Exports;

use App\Models\NilaiAkhir;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NilaiKelasExport implements FromCollection, WithHeadings
{
    protected $kelas;

    public function __construct($kelas)
    {
        $this->kelas = $kelas;
    }

    public function collection()
    {
        $rows = [];
$no = 1;
        $mahasiswa = $this->kelas->mahasiswa;
        $mataKuliah = $this->kelas->mataKuliah;

        foreach ($mahasiswa as $mhs) {
            $row = [
                'No' => $no++,
                'NIM' => $mhs->nim,
                'Nama' => $mhs->nama,
            ];

            foreach ($mataKuliah as $mk) {
                $na = NilaiAkhir::where('mahasiswa_id', $mhs->id)
                    ->where('mata_kuliah_id', $mk->id)
                    ->first();

                $row[$mk->kode . ' (NA)'] = $na?->nilai_akhir ?? '-';
                $row[$mk->kode . ' (Mutu)'] = $na?->huruf_mutu ?? '-';
                $row[$mk->kode . ' (Status)'] = $na?->status_kelulusan ?? '-';
            }

            // rata-rata
            $nilaiList = collect($mataKuliah)->map(function ($mk) use ($mhs) {
                $na = NilaiAkhir::where('mahasiswa_id', $mhs->id)
                    ->where('mata_kuliah_id', $mk->id)
                    ->first();

                return is_numeric($na?->nilai_akhir) ? $na->nilai_akhir : null;
            })->filter();

            $row['Rata-rata'] = round($nilaiList->avg() ?? 0, 2);

            $rows[] = $row;
        }

        return collect($rows);
    }

    public function headings(): array
    {
        $head = ['No', 'NIM', 'Nama'];

        foreach ($this->kelas->mataKuliah as $mk) {
            $head[] = $mk->kode . ' (NA)';
            $head[] = $mk->kode . ' (Mutu)';
            $head[] = $mk->kode . ' (Status)';
        }

        $head[] = 'Rata-rata';

        return $head;
    }
}