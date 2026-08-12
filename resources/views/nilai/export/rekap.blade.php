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
                @if($mataKuliah->hasTeori())
                <th colspan="5">Nilai Teori</th>
                @endif
                @if($mataKuliah->hasPraktikum())
                <th colspan="1">Praktikum</th>
                @endif
                <th colspan="2">Hasil Akhir</th>
            </tr>
            <tr>
                @if($mataKuliah->hasTeori())
                <th>Aktif</th>
                <th>Tugas</th>
                <th>UTS</th>
                <th>UAS</th>
                <th>NA Teori</th>
                @endif
                @if($mataKuliah->hasPraktikum())
                <th>Nilai Prak</th>
                @endif
                <th>NA</th>
                <th>Mutu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mahasiswaList as $idx => $mhs)
            @php
                $nt = $mhs->nilaiTeori->first();
                $np = $mhs->nilaiPraktikum->first();
                $na = $mhs->nilaiAkhir->first();
                $hadirOk = $na && $na->persentase_kehadiran >= 75;
            @endphp
            <tr>
                <td class="text-center">{{ $idx+1 }}</td>
                <td class="text-center">{{ $mhs->nim }}</td>
                <td>{{ $mhs->nama }}</td>
                
                @if($mataKuliah->hasTeori())
                <td class="text-center">{{ $nt?->keaktifan ?? '-' }}</td>
                <td class="text-center">{{ $nt?->tugas ?? '-' }}</td>
                <td class="text-center">{{ $nt?->uts ?? '-' }}</td>
                <td class="text-center">{{ $nt?->uas ?? '-' }}</td>
                <td class="text-center"><b>{{ $nt?->nilai_akhir_teori ?? '-' }}</b></td>
                @endif
                
                @if($mataKuliah->hasPraktikum())
                <td class="text-center"><b>{{ $np?->nilai_praktikum ?? '-' }}</b></td>
                @endif
                
                <td class="text-center"><b>{{ $na?->nilai_akhir ?? '-' }}</b></td>
                <td class="text-center">{{ $na?->huruf_mutu ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
