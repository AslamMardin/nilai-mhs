<!DOCTYPE html>
<html>
<head>
    <title>Rekap Nilai - {{ $mataKuliah->nama }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
        h2, h3, p { margin: 2px 0; }
    </style>
</head>
<body>
    <div style="text-align: center; margin-bottom: 20px;">
        <h2>Rekap Nilai Mahasiswa</h2>
        <h3>Mata Kuliah: {{ $mataKuliah->nama }} ({{ $mataKuliah->kode }})</h3>
        <p>Kelas: {{ $mataKuliah->kelas->nama }} | Kampus: {{ $mataKuliah->kampus->nama }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">NIM</th>
                <th rowspan="2">Nama Mahasiswa</th>
                <th colspan="8">Komponen Penilaian</th>
                <th colspan="3">Hasil Akhir</th>
            </tr>
            <tr>
                <th>Hadir (25%)</th>
                <th>Keaktifan</th>
                <th>Tugas (20%)</th>
                <th>UTS (25%)</th>
                <th>UAS (30%)</th>
                <th>NA Murni (100%)</th>
                <th>Bonus</th>
                <th>Nilai Finish</th>
                <th>NA</th>
                <th>Mutu</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mahasiswaList as $idx => $mhs)
            @php
                $nt = $mhs->nilaiTeori->first();
                $na = $mhs->nilaiAkhir->first();
                $persenHadir = $na ? (float)$na->persentase_kehadiran : \App\Models\Absensi::hitungPersen($mhs->id, $mataKuliah->id, $mataKuliah->total_pertemuan > 0 ? $mataKuliah->total_pertemuan : 16);
                $keaktifan = $nt ? (float)$nt->keaktifan : null;
                $tugas     = $nt ? (float)$nt->tugas : null;
                $uts       = $nt ? (float)$nt->uts : null;
                $uas       = $nt ? (float)$nt->uas : null;

                $naMurni = ($nt !== null) ? \App\Services\PenilaianService::hitungNilaiTeori(
                    $persenHadir,
                    $tugas ?? 0,
                    $uts ?? 0,
                    $uas ?? 0
                ) : null;
                $bonusKeaktifan = ($keaktifan !== null) ? \App\Services\PenilaianService::hitungBonusKeaktifan($keaktifan) : 0;
                $nilaiFinish = ($naMurni !== null) ? \App\Services\PenilaianService::hitungNilaiFinish($naMurni, $bonusKeaktifan) : null;
            @endphp
            <tr>
                <td class="text-center">{{ $idx+1 }}</td>
                <td class="text-center">{{ $mhs->nim }}</td>
                <td>{{ $mhs->nama }}</td>
                
                <td class="text-center">{{ number_format($persenHadir, 1) }}%</td>
                <td class="text-center">{{ $keaktifan !== null ? number_format($keaktifan, 2) : '-' }}</td>
                <td class="text-center">{{ $tugas !== null ? number_format($tugas, 2) : '-' }}</td>
                <td class="text-center">{{ $uts !== null ? number_format($uts, 2) : '-' }}</td>
                <td class="text-center">{{ $uas !== null ? number_format($uas, 2) : '-' }}</td>
                <td class="text-center">{{ $naMurni !== null ? number_format($naMurni, 2) : '-' }}</td>
                <td class="text-center">{{ $bonusKeaktifan > 0 ? '+' . number_format($bonusKeaktifan, 2) : '0' }}</td>
                <td class="text-center"><b>{{ $nilaiFinish !== null ? number_format($nilaiFinish, 2) : '-' }}</b></td>
                
                <td class="text-center"><b>{{ $na?->nilai_akhir !== null ? number_format($na->nilai_akhir, 2) : '-' }}</b></td>
                <td class="text-center">{{ $na?->huruf_mutu ?? '-' }}</td>
                <td class="text-center">{{ $na ? ($na->status_kelulusan == 'lulus' ? 'Lulus' : 'Tidak Lulus') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
