<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Mahasiswa;

class DataMahasiswaExport implements FromCollection, WithHeadings, WithMapping
{
    protected $kampusId;
    protected $kelasId;
    protected $search;

    public function __construct($kampusId, $kelasId, $search)
    {
        $this->kampusId = $kampusId;
        $this->kelasId = $kelasId;
        $this->search = $search;
    }

    public function collection()
    {
        return Mahasiswa::with(['kampus', 'kelas'])
            ->when($this->kampusId, fn($q, $k) => $q->where('kampus_id', $k))
            ->when($this->kelasId, fn($q, $k) => $q->where('kelas_id', $k))
            ->when($this->search, fn($q, $s) => $q->where(fn($q2) => $q2->where('nama', 'like', "%$s%")->orWhere('nim', 'like', "%$s%")))
            ->orderBy('nim')
            ->get();
    }

    public function headings(): array
    {
        return [
            'NIM',
            'Nama Mahasiswa',
            'Jenis Kelamin',
            'Kampus',
            'Kelas',
            'Email',
            'Telepon',
            'Status'
        ];
    }

    public function map($mahasiswa): array
    {
        return [
            $mahasiswa->nim,
            $mahasiswa->nama,
            $mahasiswa->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan',
            $mahasiswa->kampus ? $mahasiswa->kampus->kode : '-',
            $mahasiswa->kelas ? $mahasiswa->kelas->nama : '-',
            $mahasiswa->email,
            $mahasiswa->telepon,
            ucfirst($mahasiswa->status)
        ];
    }
}
