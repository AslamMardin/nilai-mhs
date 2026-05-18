<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Models\BobotNilai;

class NilaiMataKuliahExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $mataKuliah;
    protected $mahasiswaList;

    public function __construct($mataKuliah, $mahasiswaList)
    {
        $this->mataKuliah = $mataKuliah;
        $this->mahasiswaList = $mahasiswaList;
    }

    public function view(): View
    {
        return view('nilai.export.rekap', [
            'mataKuliah' => $this->mataKuliah,
            'mahasiswaList' => $this->mahasiswaList,
        ]);
    }

    public function title(): string
    {
        $title = preg_replace('/[^a-zA-Z0-9_\s]/', '', $this->mataKuliah->kode ?? 'Rekap');
        return substr('Rekap ' . $title, 0, 31);
    }
}
