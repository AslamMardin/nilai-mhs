<!DOCTYPE html>
<html>
<head>
    <title>Rekap Kehadiran - {{ $mataKuliah->nama }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #000; margin: 0; padding: 0; }
        .text-center { text-align: center; }
        .text-start { text-align: left; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .mb-0 { margin-bottom: 0; }
        .mb-2 { margin-bottom: 10px; }
        .mb-4 { margin-bottom: 20px; }
        
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .header-table td { border: none !important; padding: 2px 0; vertical-align: top; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data-table th, table.data-table td { border: 1px solid #000; padding: 5px 4px; vertical-align: middle; }
        table.data-table th { background-color: #f2f2f2; font-weight: bold; }
        
        .page-break { page-break-before: always; }
        
        /* Absensi Status Colors */
        .sp { font-weight: bold; padding: 2px 4px; border-radius: 3px; }
        .sp-H { background-color: #dcfce7; color: #166534; }
        .sp-T { background-color: #fef9c3; color: #854d0e; }
        .sp-S { background-color: #dbeafe; color: #1e40af; }
        .sp-I { background-color: #f1f5f9; color: #475569; }
        .sp-A { background-color: #fee2e2; color: #991b1b; }
        
        .badge-success { background-color: #dcfce7; color: #166534; padding: 2px 5px; border-radius: 3px; font-weight: bold; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; padding: 2px 5px; border-radius: 3px; font-weight: bold; }
        
        .signature-section { width: 100%; margin-top: 40px; border-collapse: collapse; }
        .signature-section td { border: none !important; text-align: center; vertical-align: top; width: 33%; }
    </style>
</head>
<body>

    {{-- KOP SURAT / HEADER --}}
    <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 15px;">
        <h2 style="margin: 0; font-size: 16px; text-transform: uppercase;">Laporan Rekapitulasi Kehadiran Mahasiswa</h2>
        <h3 style="margin: 3px 0 0 0; font-size: 13px; text-transform: uppercase;">{{ $mataKuliah->kampus->nama }}</h3>
    </div>

    <table class="header-table">
        <tr>
            <td class="fw-bold" style="width: 100px;">Mata Kuliah</td>
            <td>: {{ $mataKuliah->nama }} ({{ $mataKuliah->kode }})</td>
            <td class="fw-bold" style="width: 120px;">Dosen Pengampu</td>
            <td>: {{ auth()->user()->namalengkap ?? auth()->user()->name }}</td>
        </tr>
        <tr>
            <td class="fw-bold">Kelas / Kampus</td>
            <td>: {{ $mataKuliah->kelas->nama }} / {{ $mataKuliah->kampus->kode }}</td>
            <td class="fw-bold">Total Pertemuan</td>
            <td>: {{ $mataKuliah->total_pertemuan }} Pertemuan</td>
        </tr>
    </table>

    {{-- SECTION 1: RINGKASAN REKAP --}}
    <h3 style="margin: 15px 0 5px 0; font-size: 12px; border-bottom: 1px solid #ddd; padding-bottom: 3px;">I. Rekapitulasi Kehadiran</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th style="width: 90px;">NIM</th>
                <th>Nama</th>
                <th style="width: 35px;">H</th>
                <th style="width: 35px;">T</th>
                <th style="width: 35px;">S</th>
                <th style="width: 35px;">I</th>
                <th style="width: 35px;">A</th>
                <th style="width: 60px;">Poin</th>
                <th style="width: 60px;">%</th>
                <th style="width: 90px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekap as $i => $r)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td class="text-center"><code>{{ $r['mahasiswa']->nim }}</code></td>
                    <td>{{ $r['mahasiswa']->nama }}</td>
                    <td class="text-center">{{ $r['hitung']['H'] }}</td>
                    <td class="text-center">{{ $r['hitung']['T'] }}</td>
                    <td class="text-center">{{ $r['hitung']['S'] }}</td>
                    <td class="text-center">{{ $r['hitung']['I'] }}</td>
                    <td class="text-center">{{ $r['hitung']['A'] }}</td>
                    <td class="text-center fw-bold">{{ $r['poin'] }} / {{ $mataKuliah->total_pertemuan * 2 }}</td>
                    <td class="text-center fw-bold">{{ $r['persen'] }}%</td>
                    <td class="text-center">
                        @if ($r['lolos'])
                            <span class="badge-success">Lolos</span>
                        @else
                            <span class="badge-danger">Tidak Lolos</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- SECTION 2: DETAIL GRID PER PERTEMUAN --}}
    <div class="page-break"></div>

    <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 15px;">
        <h2 style="margin: 0; font-size: 16px; text-transform: uppercase;">Detail Kehadiran per Pertemuan</h2>
        <h3 style="margin: 3px 0 0 0; font-size: 13px; text-transform: uppercase;">{{ $mataKuliah->kampus->nama }}</h3>
    </div>

    <table class="header-table">
        <tr>
            <td class="fw-bold" style="width: 100px;">Mata Kuliah</td>
            <td>: {{ $mataKuliah->nama }} ({{ $mataKuliah->kode }})</td>
            <td class="fw-bold" style="width: 120px;">Dosen Pengampu</td>
            <td>: {{ auth()->user()->namalengkap ?? auth()->user()->name }}</td>
        </tr>
        <tr>
            <td class="fw-bold">Kelas / Kampus</td>
            <td>: {{ $mataKuliah->kelas->nama }} / {{ $mataKuliah->kampus->kode }}</td>
            <td class="fw-bold">Total Pertemuan</td>
            <td>: {{ $mataKuliah->total_pertemuan }} Pertemuan</td>
        </tr>
    </table>

    <h3 style="margin: 15px 0 5px 0; font-size: 12px; border-bottom: 1px solid #ddd; padding-bottom: 3px;">II. Matriks Detail Kehadiran</h3>
    <table class="data-table" style="font-size: 10px;">
        <thead>
            <tr>
                <th class="text-start" style="padding-left: 5px;">Mahasiswa</th>
                @for ($p = 1; $p <= $mataKuliah->total_pertemuan; $p++)
                    <th style="width: 45px;">
                        <div>{{ $p }}</div>
                        @if (isset($tanggalPertemuan[$p]))
                            <div style="font-size: 8px; font-weight: normal;">
                                {{ \Carbon\Carbon::parse($tanggalPertemuan[$p])->format('d/m') }}
                            </div>
                        @else
                            <div style="font-size: 8px; font-weight: normal; color: red;">-</div>
                        @endif
                    </th>
                @endfor
                <th style="width: 50px;">%</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekap as $r)
                <tr>
                    <td class="fw-bold" style="padding-left: 5px;">{{ $r['mahasiswa']->nama }}</td>
                    @for ($p = 1; $p <= $mataKuliah->total_pertemuan; $p++)
                        @php $a=$r['absensi'][$p]??null; @endphp
                        <td class="text-center">
                            @if ($a)
                                <span class="sp sp-{{ $a->status }}">{{ $a->status }}</span>
                            @else
                                <span style="color: #888;">—</span>
                            @endif
                        </td>
                    @endfor
                    <td class="text-center fw-bold">{{ $r['persen'] }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TANDA TANGAN --}}
    <table class="signature-section">
        <tr>
            <td>
                <p>Mengetahui,<br>Ketua Program Studi</p>
                <br><br><br>
                <p class="fw-bold mb-0">___________________________</p>
                <p style="color: #666; font-size: 9px;">NIDN. .........................</p>
            </td>
            <td></td>
            <td>
                <p>Kota Cetak, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>Dosen Pengampu</p>
                <br><br><br>
                <p class="fw-bold mb-0">{{ auth()->user()->namalengkap ?? auth()->user()->name }}</p>
                <p style="color: #666; font-size: 9px;">NIDN. .........................</p>
            </td>
        </tr>
    </table>

</body>
</html>
