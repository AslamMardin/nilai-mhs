@extends('layouts.app')
@section('title', 'Rekap Nilai')
@section('page-title', 'Nilai · ' . $mataKuliah->nama)
@section('content')

    {{-- Header --}}
    @if(session('success'))
        <div class="alert alert-success py-2 mb-3" style="font-size:13px">
            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
        </div>
    @endif
    <div class="card mb-3">
        <div class="card-body py-2">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="flex-grow-1">
                    <span class="fw-700">{{ $mataKuliah->nama }}</span>
                    <span class="badge bg-light text-dark border ms-1">{{ $mataKuliah->kode }}</span>
                    <div class="text-muted small mt-1">
                        {{ $mataKuliah->kampus->kode }} · {{ $mataKuliah->kelas->nama }} ·
                        {{ $mataKuliah->sks }} SKS · Jenis: <strong>{{ $mataKuliah->label_jenis }}</strong>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">

                    <a href="{{ route('nilai.form-teori', $mataKuliah->id) }}" class="btn btn-sm btn-primary"><i
                            class="bi bi-pencil-square me-1"></i>Input Nilai</a>
                    <a href="{{ route('nilai.form-keaktifan', $mataKuliah->id) }}"
                        class="btn btn-sm btn-outline-success"><i class="bi bi-ui-checks me-1"></i>Keaktifan</a>
                    <a href="{{ route('nilai.form-tugas', $mataKuliah->id) }}" class="btn btn-sm btn-outline-primary"><i
                            class="bi bi-journal-text me-1"></i>Tugas</a>

                    <form method="POST" action="{{ route('nilai.kalkulasi-ulang', $mataKuliah->id) }}" class="d-inline" onsubmit="return confirm('Kalkulasi ulang seluruh nilai mahasiswa pada mata kuliah ini?')">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-arrow-clockwise me-1"></i>Kalkulasi Ulang
                        </button>
                    </form>

                    {{-- <a href="{{ route('nilai.excel', $mataKuliah->id) }}" class="btn btn-sm btn-outline-success"><i class="bi bi-file-earmark-excel me-1"></i>Excel</a> --}}
                    <a href="{{ route('laporan.rekap-mk', $mataKuliah->id) }}" class="btn btn-sm btn-outline-info"><i
                            class="bi bi-bar-chart me-1"></i>Laporan</a>
                    <a target="_blank" href="{{ route('nilai.pdf', $mataKuliah->id) }}" class="btn btn-sm btn-outline-danger"><i
                            class="bi bi-file-earmark-pdf me-1"></i>Download PDF</a>
                    <a href="{{ route('nilai.pilih') }}" class="btn btn-sm btn-outline-secondary"><i
                            class="bi bi-arrow-left me-1"></i>Kembali</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Info bobot --}}
    <div class="alert alert-light border py-2 mb-3" style="font-size:12px">
        <strong>Bobot Penilaian 100%:</strong>
        Hadir {{ $bobot->kehadiran ?? 25 }}% +
        Tugas {{ $bobot->tugas ?? 20 }}% +
        UTS {{ $bobot->uts ?? 25 }}% +
        UAS {{ $bobot->uas ?? 30 }}% = 100%
        &nbsp;|&nbsp; <strong>Bonus Keaktifan:</strong> Keaktifan × 10%
        &nbsp;|&nbsp; <strong>Nilai Finish:</strong> MIN(100, NA Murni + Bonus Keaktifan)
        &nbsp;|&nbsp; <strong>Syarat Lulus:</strong> Kehadiran ≥ 60% <strong>DAN</strong> Nilai Akhir ≥ 55
    </div>

    {{-- Statistik ringkas --}}
    @php
        $totalMhs = $mahasiswaList->count();
        $sudahDinilai = $mahasiswaList->filter(fn($m) => $m->nilaiAkhir->isNotEmpty())->count();
        $lulus = $mahasiswaList->filter(fn($m) => $m->nilaiAkhir->first()?->status_kelulusan == 'lulus')->count();
        $rataRata = $mahasiswaList
            ->filter(fn($m) => $m->nilaiAkhir->isNotEmpty())
            ->avg(fn($m) => $m->nilaiAkhir->first()->nilai_akhir);
    @endphp
    <div class="row g-3 mb-3">
        <div class="col-3">
            <div class="card text-center py-2">
                <div class="fw-700 text-primary fs-5">{{ $totalMhs }}</div>
                <div class="text-muted small">Mahasiswa</div>
            </div>
        </div>
        <div class="col-3">
            <div class="card text-center py-2">
                <div class="fw-700 text-info fs-5">{{ $sudahDinilai }}</div>
                <div class="text-muted small">Sudah Dinilai</div>
            </div>
        </div>
        <div class="col-3">
            <div class="card text-center py-2">
                <div class="fw-700 text-success fs-5">{{ $lulus }}</div>
                <div class="text-muted small">Lulus</div>
            </div>
        </div>
        <div class="col-3">
            <div class="card text-center py-2">
                <div class="fw-700 text-warning fs-5">{{ number_format($rataRata ?? 0, 1) }}</div>
                <div class="text-muted small">Rata-rata NA</div>
            </div>
        </div>
    </div>

    {{-- Tabel Nilai --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-table text-primary me-1"></i>Daftar Nilai Mahasiswa</span>
            <span class="badge bg-secondary">{{ $totalMhs }} mahasiswa</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:13px">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3 text-center" style="width:35px">#</th>
                            <th style="min-width:170px">NIM / Nama Mahasiswa</th>
                            <th class="text-center" style="font-size:11px;background:#f0f7ff" title="Kehadiran × 25%">Hadir<br><span class="text-muted fw-normal">({{ $bobot->kehadiran ?? 25 }}%)</span></th>
                            <th class="text-center" style="font-size:11px;background:#f0f7ff" title="Skor Keaktifan Mahasiswa">Keaktifan<br><span class="badge bg-info-subtle text-info-emphasis border px-1" style="font-size:9px">Bonus 10%</span></th>
                            <th class="text-center" style="font-size:11px;background:#f0f7ff" title="Nilai Tugas × 20%">Tugas<br><span class="text-muted fw-normal">({{ $bobot->tugas ?? 20 }}%)</span></th>
                            <th class="text-center" style="font-size:11px;background:#f0f7ff" title="UTS × 25%">UTS<br><span class="text-muted fw-normal">({{ $bobot->uts ?? 25 }}%)</span></th>
                            <th class="text-center" style="font-size:11px;background:#f0f7ff" title="UAS × 30%">UAS<br><span class="text-muted fw-normal">({{ $bobot->uas ?? 30 }}%)</span></th>
                            <th class="text-center border-start fw-600" style="font-size:11px;background:#eef4fb" title="NA Murni = (Hadir×0.25) + (Tugas×0.20) + (UTS×0.25) + (UAS×0.30)">NA Murni<br><span class="text-muted fw-normal">(100%)</span></th>
                            <th class="text-center" style="font-size:11px;background:#eef4fb" title="Bonus Keaktifan = Keaktifan × 10%">Bonus</th>
                            <th class="text-center fw-bold" style="font-size:11px;background:#e2eeff" title="Nilai Finish = MIN(100, NA Murni + Bonus)">Nilai Finish</th>
                            <th class="text-center border-start fw-700" style="font-size:11px;background:#fff0f0" title="Nilai Akhir Final">NA</th>
                            <th class="text-center" style="font-size:11px;background:#fff0f0" title="Huruf Mutu">Mutu</th>
                            <th class="text-center" style="font-size:11px;background:#fff0f0" title="Status Kelulusan">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mahasiswaList as $idx => $mhs)
                            @php
                                $nt = $mhs->nilaiTeori->first();
                                $na = $mhs->nilaiAkhir->first();
                                $totalPertemuan = $mataKuliah->total_pertemuan > 0 ? $mataKuliah->total_pertemuan : 16;
                                $persenHadir = $na ? (float)$na->persentase_kehadiran : \App\Models\Absensi::hitungPersen($mhs->id, $mataKuliah->id, $totalPertemuan);
                                
                                $keaktifan = $nt ? (float)$nt->keaktifan : null;
                                $tugas     = $nt ? (float)$nt->tugas : null;
                                $uts       = $nt ? (float)$nt->uts : null;
                                $uas       = $nt ? (float)$nt->uas : null;

                                // 1. NA Murni = (Hadir * 0.25) + (Tugas * 0.20) + (UTS * 0.25) + (UAS * 0.30)
                                $naMurni = ($nt !== null) ? \App\Services\PenilaianService::hitungNilaiTeori(
                                    $persenHadir,
                                    $tugas ?? 0,
                                    $uts ?? 0,
                                    $uas ?? 0
                                ) : null;

                                // 2. Bonus Keaktifan = Keaktifan * 10%
                                $bonusKeaktifan = ($keaktifan !== null) ? \App\Services\PenilaianService::hitungBonusKeaktifan($keaktifan) : 0;

                                // 3. Nilai Finish = MIN(100, NA Murni + Bonus Keaktifan)
                                $nilaiFinish = ($naMurni !== null) ? \App\Services\PenilaianService::hitungNilaiFinish($naMurni, $bonusKeaktifan) : null;
                            @endphp
                            <tr>
                                <td class="ps-3 text-center text-muted">{{ $idx + 1 }}</td>
                                <td>
                                    <a href="{{ route('mahasiswa.show', $mhs->id) }}"
                                        class="text-decoration-none text-dark">
                                        <div class="fw-600">{{ $mhs->nama }}</div>
                                        <code style="font-size:11px;color:#64748b">{{ $mhs->nim }}</code>
                                    </a>
                                </td>

                                {{-- Hadir (25%) --}}
                                <td class="text-center border-start">
                                    <span class="fw-500">{{ number_format($persenHadir, 1) }}</span>
                                </td>

                                {{-- Keaktifan (Bonus 10%) --}}
                                <td class="text-center">
                                    {{ $keaktifan !== null ? number_format($keaktifan, 2) : '—' }}
                                </td>

                                {{-- Tugas (20%) --}}
                                <td class="text-center fw-500">
                                    {{ $tugas !== null ? number_format($tugas, 2) : '—' }}
                                </td>

                                {{-- UTS (25%) --}}
                                <td class="text-center">
                                    {{ $uts !== null ? number_format($uts, 2) : '—' }}
                                </td>

                                {{-- UAS (30%) --}}
                                <td class="text-center">
                                    {{ $uas !== null ? number_format($uas, 2) : '—' }}
                                </td>

                                {{-- NA Murni (100%) --}}
                                <td class="text-center border-start fw-600" style="background:#fcfdff">
                                    {{ $naMurni !== null ? number_format($naMurni, 2) : '—' }}
                                </td>

                                {{-- Bonus --}}
                                <td class="text-center" style="background:#fcfdff">
                                    @if($bonusKeaktifan > 0)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size:11px">+{{ number_format($bonusKeaktifan, 2) }}</span>
                                    @elseif($keaktifan !== null)
                                        <span class="text-muted">0</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Nilai Finish --}}
                                <td class="text-center fw-bold border-end" style="background:#eef6ff;font-size:14px">
                                    <span class="text-primary">{{ $nilaiFinish !== null ? number_format($nilaiFinish, 2) : '—' }}</span>
                                </td>

                                {{-- NA --}}
                                <td class="text-center border-start fw-700" style="font-size:15px;background:#fff8f8">
                                    {{ $na?->nilai_akhir !== null ? number_format($na->nilai_akhir, 2) : '—' }}
                                </td>

                                {{-- Mutu --}}
                                <td class="text-center" style="background:#fff8f8">
                                    @if ($na?->huruf_mutu)
                                        <span class="badge badge-{{ strtolower($na->huruf_mutu) }} px-2">{{ $na->huruf_mutu }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="text-center" style="background:#fff8f8">
                                    @if ($na)
                                        @if ($na->status_kelulusan == 'lulus')
                                            <span class="badge bg-success"><i class="bi bi-check me-1"></i>Lulus</span>
                                        @elseif($na->status_kelulusan == 'tidak_lulus')
                                            <span class="badge bg-danger" title="{{ $na->keterangan_gagal }}"><i class="bi bi-x me-1"></i>Tidak Lulus</span>
                                        @else
                                            <span class="badge bg-secondary">Belum</span>
                                        @endif
                                    @else
                                        <span class="badge bg-light text-muted border" style="font-size:11px">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center text-muted py-4">Belum ada mahasiswa terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
