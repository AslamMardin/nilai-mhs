<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Mahasiswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // skip kalau kosong
        if (empty($row['nim']) || empty($row['nama'])) {
            return null;
        }

        // 🔥 skip kalau NIM sudah ada
        $exists = Mahasiswa::where('nim', $row['nim'])->exists();
        if ($exists) {
            return null;
        }

        $kelas = Kelas::first();

        // 🔥 kalau tidak ada kelas → skip
        if (!$kelas) {
            return null;
        }

        return new Mahasiswa([
            'kampus_id' => session('kampus_id'),
            'kelas_id' => $kelas->id,
            'nim' => $row['nim'],
            'nama' => $row['nama'],
            'jenis_kelamin' => strtoupper($row['jenis_kelamin'] ?? ''),
            'email' => $row['email'] ?? null,
            'telepon' => $row['telepon'] ?? null,
            'alamat' => $row['alamat'] ?? null,
            'tanggal_lahir' => $row['tanggal_lahir'] ?? null,
            'tempat_lahir' => $row['tempat_lahir'] ?? null,
            'status' => $row['status'] ?? 'aktif',
        ]);
    }
}