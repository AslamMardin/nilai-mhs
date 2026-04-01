@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
{{-- ── Statistik Ringkas ───────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:#1a4a7a;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-num">{{ $totalMahasiswa }}</div>
                    <div class="stat-label">Total Mahasiswa</div>
                </div>
                <i class="bi bi-people stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:#0f766e;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-num">{{ $totalMataKuliah }}</div>
                    <div class="stat-label">Mata Kuliah Aktif</div>
                </div>
                <i class="bi bi-book stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:#b45309;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-num">{{ $totalKelas }}</div>
                    <div class="stat-label">Kelas (5 Kampus)</div>
                </div>
                <i class="bi bi-door-open stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background:#7c3aed;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-num">{{ $persenLulus }}%</div>
                    <div class="stat-label">Tingkat Kelulusan</div>
                </div>
                <i class="bi bi-patch-check stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- ── Rekap Per Kampus ──────────────────────────────────── --}}
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <span><i class="bi bi-building me-1 text-primary"></i> Rekap Per Kampus</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Kampus</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Mahasiswa</th>
                            <th class="text-center">Matkul</th>
                            <th class="text-center">% Lulus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekapKampus as $r)
                        <tr>
                            <td class="ps-3">
                                <span class="fw-600">{{ $r['kampus']->kode }}</span>
                                <div class="text-muted small">{{ Str::limit($r['kampus']->nama, 35) }}</div>
                            </td>
                            <td class="text-center">{{ $r['kampus']->kelas->count() }}</td>
                            <td class="text-center">{{ $r['total_mahasiswa'] }}</td>
                            <td class="text-center">{{ $r['kampus']->mataKuliah->count() }}</td>
                            <td class="text-center">
                                <span class="badge {{ $r['persentase_lulus'] >= 75 ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $r['persentase_lulus'] }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ── Distribusi Nilai ─────────────────────────────────── --}}
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header py-3">
                <i class="bi bi-pie-chart me-1 text-success"></i> Distribusi Huruf Mutu
            </div>
            <div class="card-body">
                @foreach(['A'=>'success','B'=>'primary','C'=>'warning','D'=>'orange','E'=>'danger'] as $huruf => $warna)
                @php
                    $jumlah = $distribusiHuruf[$huruf] ?? 0;
                    $total  = array_sum($distribusiHuruf ?: [1]);
                    $persen = $total > 0 ? round(($jumlah / $total) * 100) : 0;
                @endphp
                <div class="d-flex align-items-center mb-2">
                    <span class="badge badge-{{ $huruf }} me-2" style="width:28px">{{ $huruf }}</span>
                    <div class="flex-grow-1">
                        <div class="progress" style="height:10px; border-radius:6px;">
                            <div class="progress-bar bg-{{ $warna }}" style="width:{{ $persen }}%"></div>
                        </div>
                    </div>
                    <span class="ms-2 text-muted small" style="width:48px;text-align:right">
                        {{ $jumlah }} mhs
                    </span>
                </div>
                @endforeach

                <hr class="my-3">
                <div class="row text-center g-2">
                    <div class="col-6">
                        <div class="p-2 rounded" style="background:#f0fdf4;">
                            <div class="fw-700 text-success fs-5">{{ $statLulus['lulus'] }}</div>
                            <div class="text-muted small">Lulus</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded" style="background:#fff1f2;">
                            <div class="fw-700 text-danger fs-5">{{ $statLulus['tidak_lulus'] }}</div>
                            <div class="text-muted small">Tidak Lulus</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Aktivitas Terkini ─────────────────────────────────── --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header py-3">
                <i class="bi bi-clock-history me-1 text-info"></i> Mata Kuliah — Input Nilai Terbaru
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Mata Kuliah</th>
                            <th>Kampus / Kelas</th>
                            <th class="text-center">Mahasiswa</th>
                            <th class="text-center">Sudah Dinilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mataKuliahTerkini as $mk)
                        <tr>
                            <td class="ps-3">
                                <span class="badge bg-light text-dark border me-1">{{ $mk->kode }}</span>
                                {{ $mk->nama }}
                            </td>
                            <td>
                                <span class="text-muted small">{{ $mk->kampus->kode }}</span>
                                / {{ $mk->kelas->nama }}
                            </td>
                            <td class="text-center">{{ $mk->mahasiswa->count() }}</td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark">{{ $mk->nilaiAkhir->count() }}</span>
                            </td>
                            <td>
                                <a href="{{ route('nilai.index', $mk->id) }}"
                                   class="btn btn-xs btn-outline-primary btn-sm py-0 px-2">
                                    <i class="bi bi-pencil-square"></i> Nilai
                                </a>
                                <a href="{{ route('absensi.index', $mk->id) }}"
                                   class="btn btn-xs btn-outline-secondary btn-sm py-0 px-2">
                                    <i class="bi bi-calendar-check"></i> Absensi
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada data mata kuliah.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
