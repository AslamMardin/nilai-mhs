@extends('layouts.app')
@section('title', 'Rekap Kehadiran')
@section('page-title', 'Absensi · Rekap Kehadiran')
@section('content')
    {{-- Kop Laporan (Hanya Muncul saat Cetak) --}}
    <div class="d-none d-print-block mb-4">
        <div class="row align-items-center mb-3">
            <div class="col-12 text-center border-bottom pb-2">
                <h4 class="fw-bold mb-0">LAPORAN REKAPITULASI KEHADIRAN MAHASISWA</h4>
                <p class="text-uppercase fw-semibold mb-0" style="font-size: 14px;">{{ $mataKuliah->kampus->nama }}</p>
            </div>
        </div>
        <div class="row mb-3" style="font-size: 13px;">
            <div class="col-6">
                <table class="table table-borderless table-sm m-0">
                    <tr>
                        <td class="p-0 fw-semibold" style="width: 120px;">Mata Kuliah</td>
                        <td class="p-0">: {{ $mataKuliah->nama }} ({{ $mataKuliah->kode }})</td>
                    </tr>
                    <tr>
                        <td class="p-0 fw-semibold">Kelas</td>
                        <td class="p-0">: {{ $mataKuliah->kelas->nama }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-6 text-end">
                <table class="table table-borderless table-sm m-0 d-inline-block text-start">
                    <tr>
                        <td class="p-0 fw-semibold" style="width: 120px;">Dosen Pengampu</td>
                        <td class="p-0">: {{ auth()->user()->namalengkap ?? auth()->user()->name }}</td>
                    </tr>
                    <tr>
                        <td class="p-0 fw-semibold">Total Pertemuan</td>
                        <td class="p-0">: {{ $mataKuliah->total_pertemuan }} Pertemuan</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-3 d-print-none">
        <div class="card-body py-2 d-flex align-items-center gap-3">
            <div class="flex-grow-1">
                <span class="fw-700">{{ $mataKuliah->nama }}</span>
                <span class="badge bg-light text-dark border ms-1">{{ $mataKuliah->kode }}</span>
                <div class="text-muted small">{{ $mataKuliah->kampus->kode }} · {{ $mataKuliah->kelas->nama }} ·
                    {{ $mataKuliah->total_pertemuan }} Pertemuan</div>
            </div>
            <a href="{{ route('absensi.index', $mataKuliah->id) }}" class="btn btn-sm btn-outline-primary"><i
                    class="bi bi-pencil-square me-1"></i>Input Absensi</a>
            <button onclick="window.print()" class="btn btn-sm btn-primary"><i
                    class="bi bi-printer me-1"></i>Cetak Rekap</button>
            <a href="{{ route('absensi.pdf', $mataKuliah->id) }}" target="_blank" class="btn btn-sm btn-outline-danger"><i
                    class="bi bi-file-pdf me-1"></i>Ekspor PDF</a>
            <a href="{{ route('absensi.pilih') }}" class="btn btn-sm btn-outline-secondary"><i
                    class="bi bi-arrow-left me-1"></i>Kembali</a>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="row g-3 mb-3 d-print-none">
        @php
            $totalLolos = $rekap->where('lolos', true)->count();
            $totalTidak = $rekap->where('lolos', false)->count();
            $rataPersen = $rekap->avg('persen') ?? 0;
        @endphp
        <div class="col-4">
            <div class="card text-center py-2">
                <div class="fw-700 text-success fs-4">{{ $totalLolos }}</div>
                <div class="text-muted small">Lolos Kehadiran</div>
            </div>
        </div>
        <div class="col-4">
            <div class="card text-center py-2">
                <div class="fw-700 text-danger fs-4">{{ $totalTidak }}</div>
                <div class="text-muted small">Tidak Lolos</div>
            </div>
        </div>
        <div class="col-4">
            <div class="card text-center py-2">
                <div class="fw-700 text-primary fs-4">{{ number_format($rataPersen, 1) }}%</div>
                <div class="text-muted small">Rata-rata Kehadiran</div>
            </div>
        </div>
    </div>

    {{-- Tabel Rekap --}}
    <div class="card mb-3">
        <div class="card-header"><i class="bi bi-person-lines-fill text-primary me-1"></i>Rekap Kehadiran per Mahasiswa
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th class="text-center">H</th>
                            <th class="text-center">T</th>
                            <th class="text-center">S</th>
                            <th class="text-center">I</th>
                            <th class="text-center">A</th>
                            <th class="text-center">Poin</th>
                            <th class="text-center">%</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rekap as $i => $r)
                            <tr>
                                <td class="ps-3 text-muted">{{ $i + 1 }}</td>
                                <td><code class="small">{{ $r['mahasiswa']->nim }}</code></td>
                                <td>{{ $r['mahasiswa']->nama }}</td>
                                <td class="text-center"><span class="sp sp-H">{{ $r['hitung']['H'] }}</span></td>
                                <td class="text-center"><span class="sp sp-T">{{ $r['hitung']['T'] }}</span></td>
                                <td class="text-center"><span class="sp sp-S">{{ $r['hitung']['S'] }}</span></td>
                                <td class="text-center"><span class="sp sp-I">{{ $r['hitung']['I'] }}</span></td>
                                <td class="text-center"><span class="sp sp-A">{{ $r['hitung']['A'] }}</span></td>
                                <td class="text-center fw-600">{{ $r['poin'] }} /
                                    {{ $mataKuliah->total_pertemuan * 2 }}</td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center gap-1">
                                        <div class="progress flex-grow-1" style="height:6px">
                                            <div class="progress-bar {{ $r['lolos'] ? 'bg-success' : 'bg-danger' }}"
                                                style="width:{{ $r['persen'] }}%"></div>
                                        </div>
                                        <span class="fw-600"
                                            style="font-size:12px;white-space:nowrap">{{ $r['persen'] }}%</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if ($r['lolos'])
                                        <span class="badge bg-success">Lolos</span>
                                    @else
                                        <span class="badge bg-danger" title="Kurang dari 75%">Tidak Lolos</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Grid Detail Pertemuan --}}
    <div class="card print-page-break">
        <div class="card-header"><i class="bi bi-grid me-1 text-secondary"></i>Detail per Pertemuan</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center mb-0" style="font-size:11px">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start ps-2" style="min-width:140px">Mahasiswa</th>
                            @for ($p = 1; $p <= $mataKuliah->total_pertemuan; $p++)
                                <th style="width:70px">
                                    <div class="fw-600">{{ $p }}</div>

                                    @if (isset($tanggalPertemuan[$p]))
                                        <div class="text-muted" style="font-size:10px">
                                            {{ \Carbon\Carbon::parse($tanggalPertemuan[$p])->format('d/m') }}
                                        </div>
                                    @else
                                        <div class="text-danger" style="font-size:10px">-</div>
                                    @endif
                                </th>
                            @endfor
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rekap as $r)
                            <tr>
                                <td class="text-start ps-2 fw-500">{{ $r['mahasiswa']->nama }}</td>
                                @for ($p = 1; $p <= $mataKuliah->total_pertemuan; $p++)
                                    @php $a=$r['absensi'][$p]??null; @endphp
                                    <td>
                                        @if ($a)
                                            <span class="sp sp-{{ $a->status }}"
                                            style="width:24px;height:18px;font-size:10px">{{ $a->status }}</span>@else<span
                                                class="text-muted">—</span>
                                        @endif
                                    </td>
                                @endfor
                                <td><span class="badge {{ $r['lolos'] ? 'bg-success' : 'bg-danger' }}"
                                        style="font-size:10px">{{ $r['persen'] }}%</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tanda Tangan (Hanya Muncul saat Cetak) --}}
    <div class="d-none d-print-block mt-5">
        <div class="row justify-content-between" style="font-size: 13px;">
            <div class="col-4 text-center">
                <p class="mb-5">Mengetahui,<br>Ketua Program Studi</p>
                <p class="fw-bold mb-0">___________________________</p>
                <p class="text-muted small">NIDN. .........................</p>
            </div>
            <div class="col-4 text-center">
                <p class="mb-5">Kota Cetak, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>Dosen Pengampu</p>
                <p class="fw-bold mb-0">{{ auth()->user()->namalengkap ?? auth()->user()->name }}</p>
                <p class="text-muted small">NIDN. .........................</p>
            </div>
        </div>
    </div>

@push('styles')
<style>
@media print {
    /* Hide dashboard layout elements */
    #sb, #tb, .d-print-none {
        display: none !important;
    }
    #main {
        margin-left: 0 !important;
        padding-top: 0 !important;
    }
    .cw {
        padding: 0 !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
    }
    .card-header {
        background-color: transparent !important;
        border-bottom: 2px solid #000 !important;
        font-weight: bold !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        font-size: 14px !important;
    }
    /* Page settings - Force landscape layout */
    @page {
        size: landscape;
        margin: 15mm 15mm;
    }
    body {
        background: #fff !important;
        color: #000 !important;
        font-size: 12px;
    }
    /* Page break for the second section */
    .print-page-break {
        page-break-before: always;
        margin-top: 20px;
    }
    /* Table styling for physical print */
    table.table {
        width: 100% !important;
        border-collapse: collapse !important;
    }
    table.table th, table.table td {
        border: 1px solid #000 !important;
        color: #000 !important;
        background: #fff !important;
        padding: 6px 4px !important;
        vertical-align: middle !important;
    }
    table.table th {
        background-color: #f2f2f2 !important;
    }
    /* Reset borders for the header kop information */
    table.table-borderless, table.table-borderless td, table.table-borderless th {
        border: none !important;
        background: transparent !important;
        padding: 2px 0 !important;
    }
    /* Ensure status badges print correctly */
    .sp {
        border: 1px solid #aaa !important;
        background: transparent !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    .badge {
        border: 1px solid #333 !important;
        background: transparent !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
}
</style>
@endpush
@endsection
