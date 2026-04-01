@extends('layouts.app')

@section('title', 'Rekap Per Kampus')
@section('page-title', 'Laporan — Rekap Per Kampus')

@section('content')
<div class="row g-3 mb-4">
    @foreach($statistik as $stat)
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header py-3 d-flex align-items-center gap-2">
                <i class="bi bi-building text-primary"></i>
                <span class="fw-600">{{ $stat['kampus']->nama }}</span>
                <span class="badge bg-light text-dark border ms-auto">{{ $stat['kampus']->kode }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3 text-center mb-3">
                    <div class="col-4">
                        <div class="p-2 rounded" style="background:#f8fafc">
                            <div class="fs-4 fw-700 text-primary">{{ $stat['total_mahasiswa'] }}</div>
                            <div class="text-muted small">Mahasiswa</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 rounded" style="background:#f0fdf4">
                            <div class="fs-4 fw-700 text-success">{{ $stat['total_lulus'] }}</div>
                            <div class="text-muted small">Lulus</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 rounded" style="background:#fff1f2">
                            <div class="fs-4 fw-700 text-danger">{{ $stat['total_gagal'] }}</div>
                            <div class="text-muted small">Tidak Lulus</div>
                        </div>
                    </div>
                </div>

                {{-- Progress Bar Kelulusan --}}
                <div class="mb-2">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Tingkat Kelulusan</span>
                        <strong class="{{ $stat['persentase_lulus'] >= 75 ? 'text-success' : 'text-warning' }}">
                            {{ $stat['persentase_lulus'] }}%
                        </strong>
                    </div>
                    <div class="progress" style="height:12px; border-radius:8px;">
                        <div class="progress-bar {{ $stat['persentase_lulus'] >= 75 ? 'bg-success' : 'bg-warning' }}"
                             style="width:{{ $stat['persentase_lulus'] }}%"></div>
                    </div>
                </div>

                <div class="d-flex justify-content-between small text-muted mt-2">
                    <span>Rata-rata Nilai: <strong>{{ $stat['rata_rata'] }}</strong></span>
                    <span>Kelas: {{ $stat['kampus']->kelas->count() }}</span>
                    <span>Matkul: {{ $stat['kampus']->mataKuliah->count() }}</span>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Tabel detail kelas per kampus --}}
@foreach($statistik as $stat)
<div class="card mb-3">
    <div class="card-header py-2">
        <strong>{{ $stat['kampus']->kode }}</strong> — Daftar Kelas
    </div>
    <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Kelas</th>
                    <th class="text-center">Mahasiswa</th>
                    <th class="text-center">Mata Kuliah</th>
                    <th>Wali Kelas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stat['kampus']->kelas as $kelas)
                <tr>
                    <td class="ps-3">
                        <span class="badge bg-light text-dark border me-1">{{ $kelas->kode }}</span>
                        {{ $kelas->nama }}
                    </td>
                    <td class="text-center">{{ $kelas->mahasiswa->count() }}</td>
                    <td class="text-center">{{ $kelas->mataKuliah->count() }}</td>
                    <td class="text-muted small">{{ $kelas->wali_kelas ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach
@endsection
