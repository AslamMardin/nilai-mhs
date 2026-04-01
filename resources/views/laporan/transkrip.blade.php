@extends('layouts.app')

@section('title', 'Transkrip Nilai')
@section('page-title', 'Laporan — Transkrip Nilai Mahasiswa')

@section('content')
{{-- Form Cari Mahasiswa --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('laporan.transkrip') }}" class="d-flex gap-2 align-items-end">
            <div class="flex-grow-1">
                <label class="form-label small mb-1">NIM Mahasiswa</label>
                <input type="text" name="nim" class="form-control"
                       placeholder="Contoh: ITBM2024001"
                       value="{{ $nim ?? '' }}" required>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search me-1"></i> Cari
            </button>
        </form>
    </div>
</div>

@if($mahasiswa)
{{-- Header Transkrip --}}
<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted small" style="width:130px">Nama</td>
                        <td class="fw-600">: {{ $mahasiswa->nama }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small">NIM</td>
                        <td><code>: {{ $mahasiswa->nim }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted small">Kampus</td>
                        <td>: {{ $mahasiswa->kampus->nama }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small">Kelas</td>
                        <td>: {{ $mahasiswa->kelas->nama }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small">Status</td>
                        <td>
                            : <span class="badge {{ $mahasiswa->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($mahasiswa->status) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-4 text-md-end d-flex flex-column align-items-md-end justify-content-center">
                @php
                    $totalSks  = $transkrip->sum('sks');
                    $rataRata  = $transkrip->avg('nilai_akhir') ?? 0;
                    $totalLulus = $transkrip->where('status', 'lulus')->count();
                @endphp
                <div class="stat-card d-inline-block text-center px-4 py-3 rounded"
                     style="background:#1a4a7a; min-width:140px;">
                    <div class="stat-num">{{ number_format($rataRata, 2) }}</div>
                    <div class="stat-label">Rata-Rata Nilai</div>
                </div>
                <div class="mt-2 small text-muted">
                    Total SKS: {{ $totalSks }} | Lulus: {{ $totalLulus }}/{{ $transkrip->count() }} matkul
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Transkrip --}}
<div class="card">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <span><i class="bi bi-file-earmark-text text-primary me-1"></i> Transkrip Nilai</span>
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-printer me-1"></i> Cetak
        </button>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">#</th>
                    <th>Kode</th>
                    <th>Mata Kuliah</th>
                    <th class="text-center">SKS</th>
                    <th class="text-center">Teori</th>
                    <th class="text-center">Prak.</th>
                    <th class="text-center">Nilai Akhir</th>
                    <th class="text-center">Mutu</th>
                    <th class="text-center">Kehadiran</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transkrip as $idx => $t)
                <tr>
                    <td class="ps-3 text-muted">{{ $idx + 1 }}</td>
                    <td><code class="small">{{ $t['kode'] }}</code></td>
                    <td>{{ $t['mata_kuliah'] }}</td>
                    <td class="text-center">{{ $t['sks'] }}</td>
                    <td class="text-center">{{ $t['nilai_teori'] ?: '—' }}</td>
                    <td class="text-center">{{ $t['nilai_prak'] ?: '—' }}</td>
                    <td class="text-center fw-700 fs-6">{{ $t['nilai_akhir'] }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $t['huruf_mutu'] ?? 'secondary' }} px-2">
                            {{ $t['huruf_mutu'] ?? '—' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge {{ str_replace('%','',$t['kehadiran']) >= 75 ? 'bg-success' : 'bg-danger' }}">
                            {{ $t['kehadiran'] }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($t['status'] === 'lulus')
                        <i class="bi bi-check-circle-fill text-success"></i>
                        @elseif($t['status'] === 'tidak_lulus')
                        <i class="bi bi-x-circle-fill text-danger"></i>
                        @else
                        <i class="bi bi-dash-circle text-muted"></i>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">
                        Belum ada data nilai untuk mahasiswa ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($transkrip->count())
            <tfoot class="table-light fw-600">
                <tr>
                    <td colspan="3" class="ps-3 text-end">Total / Rata-rata</td>
                    <td class="text-center">{{ $transkrip->sum('sks') }}</td>
                    <td colspan="2"></td>
                    <td class="text-center">{{ number_format($transkrip->avg('nilai_akhir'), 2) }}</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@elseif($nim)
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle me-1"></i>
    Mahasiswa dengan NIM <strong>{{ $nim }}</strong> tidak ditemukan.
</div>
@else
<div class="text-center text-muted py-5">
    <i class="bi bi-search" style="font-size:48px; opacity:.3"></i>
    <p class="mt-2">Masukkan NIM untuk melihat transkrip nilai mahasiswa.</p>
</div>
@endif
@endsection
