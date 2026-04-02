<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MahasiswaExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'nim',
            'nama',
            'jenis_kelamin',
            'email',
            'telepon',
            'alamat',
            'tanggal_lahir',
            'tempat_lahir',
            'status'
        ];
    }

    public function array(): array
    {
        return [
            [
                '12345',
                'Budi Santoso',
                'L',
                'budi@mail.com',
                '08123456789',
                'Makassar',
                '2000-01-01',
                'Makassar',
                'aktif'
            ]
        ];
    }
}