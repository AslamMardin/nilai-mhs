@extends('layouts.app')

@section('title', 'Nilai — ' . $mataKuliah->nama)
@section('page-title', 'Input & Rekap Nilai')

@section('content')
<div class="d-flex align-items-start justify-content-between mb-3">
    <div>
        <h5 class="mb-0">{{ $mataKuliah->nama }}</h5>
        <small class="text-muted">
            <span class="badge bg-light text-dark border">{{ $mataKuliah->kode }}</span>
            {{ $mataKuliah->kampus->kode }} — {{ $mataKuliah->kelas->nama }} |
            {{ $mataKuliah->sks }} SKS | Jenis: <strong>{{ ucfirst(str_replace('_', ' + ', $mataKuliah->jenis)) }}</strong>
        </small>
    </div>
    <div class="d-flex gap-2">
        @if($mataKuliah->hasTeori())
        <a href="{{ route('nilai.form-teori', $mataKuliah->id) }}"
           class="btn btn-primary btn-sm">
            <i class="bi bi-pencil me-1"></i> Input Nilai Teori
        </a>
        @endif
        @if($mataKuliah->hasPraktikum())
        <a href="{{ route('nilai.form-praktikum', $mataKuliah->id) }}"
           class="btn btn-outline-success btn-sm">
            <i class="bi bi-tools me-1"></i> Input Nilai Praktikum
        </a>
        @endif
        <a href="{{ route('laporan.export-pdf', $mataKuliah->id) }}"
           class="btn btn-outline-danger btn-sm">
            <i class="bi bi-file-pdf me-1"></i> Export PDF
        </a>
    </div>
</div>

{{-- ── Keterangan Bobot ────────────────────────────────────── --}}
<div class="alert alert-light border mb-3 py-2" style="font-size:12px">
    @if($mataKuliah->hasTeori())
    <strong>Bobot Teori:</strong>
    Keaktifan 20% + Tugas 20% + UTS 25% + UAS 35%
    @endif
    @if($mataKuliah->jenis === 'teori_praktikum')
    &nbsp;|&nbsp; <strong>Nilai Akhir:</strong> Teori 50% + Praktikum 50%
    @endif
    @if($mataKuliah->jenis === 'praktikum')
    <strong>Bobot Praktikum:</strong> 100%
    @endif
    &nbsp;|&nbsp; <strong>Syarat Lulus:</strong> Kehadiran ≥ 75% dan Nilai ≥ 55
</div>

{{-- ── Tabel Nilai Mahasiswa ──────────────────────────────── --}}
<div class="card">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clipboard-data text-primary me-1"></i> Rekap Nilai Mahasiswa</span>
        <span class="badge bg-secondary">{{ $mahasiswaList->count() }} Mahasiswa</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" rowspan="2">#</th>
                        <th rowspan="2">NIM</th>
                        <th rowspan="2">Nama</th>
                        @if($mataKuliah->hasTeori())
                        <th class="text-center" colspan="5" style="border-left:1px solid #e2e8f0">
                            Nilai Teori
                        </th>
                        @endif
                        @if($mataKuliah->hasPraktikum())
                        <th class="text-center" colspan="1" style="border-left:1px solid #e2e8f0">
                            Praktikum
                        </th>
                        @endif
                        <th class="text-center" colspan="3" style="border-left:1px solid #e2e8f0">
                            Kehadiran
                        </th>
                        <th class="text-center" colspan="3" style="border-left:2px solid #1a4a7a">
                            Hasil Akhir
                        </th>
                    </tr>
                    <tr>
                        @if($mataKuliah->hasTeori())
                        <th class="text-center small" style="border-left:1px solid #e2e8f0">Aktif</th>
                        <th class="text-center small">Tugas</th>
                        <th class="text-center small">UTS</th>
                        <th class="text-center small">UAS</th>
                        <th class="text-center small">NA Teori</th>
                        @endif
                        @if($mataKuliah->hasPraktikum())
                        <th class="text-center small" style="border-left:1px solid #e2e8f0">Nilai Prak</th>
                        @endif
                        <th class="text-center small" style="border-left:1px solid #e2e8f0">Poin</th>
                        <th class="text-center small">%</th>
                        <th class="text-center small">Ket.</th>
                        <th class="text-center small" style="border-left:2px solid #1a4a7a">Nilai</th>
                        <th class="text-center small">Mutu</th>
                        <th class="text-center small">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswaList as $idx => $mhs)
                    @php
                        $nt  = $mhs->nilaiTeori->first();
                        $np  = $mhs->nilaiPraktikum->first();
                        $na  = $mhs->nilaiAkhir->first();
                    @endphp
                    <tr>
                        <td class="ps-3 text-muted">{{ $idx + 1 }}</td>
                        <td><code class="small">{{ $mhs->nim }}</code></td>
                        <td>{{ $mhs->nama }}</td>

                        @if($mataKuliah->hasTeori())
                        <td class="text-center" style="border-left:1px solid #e2e8f0">
                            {{ $nt?->keaktifan ?? '—' }}
                        </td>
                        <td class="text-center">{{ $nt?->tugas ?? '—' }}</td>
                        <td class="text-center">{{ $nt?->uts ?? '—' }}</td>
                        <td class="text-center">{{ $nt?->uas ?? '—' }}</td>
                        <td class="text-center fw-600">
                            {{ $nt?->nilai_akhir_teori ?? '—' }}
                        </td>
                        @endif

                        @if($mataKuliah->hasPraktikum())
                        <td class="text-center" style="border-left:1px solid #e2e8f0">
                            {{ $np?->nilai_praktikum ?? '—' }}
                        </td>
                        @endif

                        {{-- Kehadiran --}}
                        <td class="text-center" style="border-left:1px solid #e2e8f0">
                            {{ $na?->poin_kehadiran ?? '—' }}
                        </td>
                        <td class="text-center">
                            @if($na)
                            <span class="badge {{ $na->persentase_kehadiran >= 75 ? 'bg-success' : 'bg-danger' }}">
                                {{ $na->persentase_kehadiran }}%
                            </span>
                            @else —
                            @endif
                        </td>
                        <td class="text-center small">
                            @if($na && $na->persentase_kehadiran < 75)
                            <i class="bi bi-x-circle text-danger" title="Kehadiran < 75%"></i>
                            @elseif($na)
                            <i class="bi bi-check-circle text-success"></i>
                            @else —
                            @endif
                        </td>

                        {{-- Nilai Akhir --}}
                        <td class="text-center fw-700" style="border-left:2px solid #1a4a7a; font-size:15px">
                            {{ $na?->nilai_akhir ?? '—' }}
                        </td>
                        <td class="text-center">
                            @if($na?->huruf_mutu)
                            <span class="badge badge-{{ $na->huruf_mutu }} px-2">
                                {{ $na->huruf_mutu }}
                            </span>
                            @else —
                            @endif
                        </td>
                        <td class="text-center">
                            @if($na)
                            @if($na->status_kelulusan === 'lulus')
                            <span class="badge bg-success">Lulus</span>
                            @elseif($na->status_kelulusan === 'tidak_lulus')
                            <span class="badge bg-danger" title="{{ $na->keterangan_gagal }}">
                                Tidak Lulus
                            </span>
                            @else
                            <span class="badge bg-secondary">Belum</span>
                            @endif
                            @else
                            <span class="badge bg-light text-muted border">Belum dinilai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="15" class="text-center text-muted py-4">
                            Belum ada mahasiswa terdaftar di mata kuliah ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
