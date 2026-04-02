<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaImport implements ToModel, WithHeadingRow
{
    protected $kampusId;
    protected $kelasId;

    public $total = 0;
    public $success = 0;
    public $skipped = 0;
    public $duplicate = 0;

    public function __construct($kampusId, $kelasId)
    {
        $this->kampusId = $kampusId;
        $this->kelasId = $kelasId;
    }

    public function model(array $row)
    {
        $this->total++;

        // ❌ skip kosong
        if (empty($row['nim']) || empty($row['nama'])) {
            $this->skipped++;
            return null;
        }

        // ❌ skip duplikat
        $exists = Mahasiswa::where('nim', $row['nim'])->exists();
        if ($exists) {
            $this->duplicate++;
            return null;
        }

        $this->success++;

        return new Mahasiswa([
            'kampus_id' => $this->kampusId,
            'kelas_id' => $this->kelasId,
            'nim' => trim($row['nim']),
            'nama' => trim($row['nama']),
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