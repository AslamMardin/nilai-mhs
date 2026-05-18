@extends('layouts.app')
@section('title', 'Rekap Nilai Mata Kuliah')
@section('page-title', 'Laporan · Rekap Nilai')
@section('content')
    <div class="card mb-3">
        <div class="card-body py-2 d-flex align-items-center gap-3">
            <div class="flex-grow-1">
                <span class="fw-700">{{ $mataKuliah->nama }}</span>
                <div class="text-muted small">{{ $mataKuliah->kampus->kode }} · {{ $mataKuliah->kelas->nama }}</div>
            </div>
            <div>
                <a href="{{ route('nilai.index', $mataKuliah->id) }}" class="btn btn-sm btn-outline-secondary"><i
                        class="bi bi-arrow-left "></i>Kembali ke Nilai</a>
                <a href="{{ route('nilai.pdf', $mataKuliah->id) }}" class="btn btn-sm btn-outline-danger" target="_blank"><i
                        class="bi bi-file-earmark-pdf me-1"></i>PDF</a>
            </div>
        </div>

        <div class="row g-3 mb-3">
            @php
                $totalMhs = $rekap->count();
                $lulus = $rekap->where('status_kelulusan', 'lulus')->count();
                $tl = $rekap->where('status_kelulusan', 'tidak_lulus')->count();
                $rataNA = $rekap->avg('nilai_akhir') ?? 0;
                $rataHdr = $rekap->avg('persentase_kehadiran') ?? 0;
            @endphp
            <div class="col">
                <div class="card text-center py-2">
                    <div class="fw-700 text-primary fs-5">{{ $totalMhs }}</div>
                    <div class="text-muted small">Total</div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center py-2">
                    <div class="fw-700 text-success fs-5">{{ $lulus }}</div>
                    <div class="text-muted small">Lulus</div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center py-2">
                    <div class="fw-700 text-danger fs-5">{{ $tl }}</div>
                    <div class="text-muted small">Tidak Lulus</div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center py-2">
                    <div class="fw-700 text-warning fs-5">{{ number_format($rataNA, 1) }}</div>
                    <div class="text-muted small">Rata-rata NA</div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center py-2">
                    <div class="fw-700 text-info fs-5">{{ number_format($rataHdr, 1) }}%</div>
                    <div class="text-muted small">Rata-rata Hadir</div>
                </div>
            </div>
        </div>

        {{-- Distribusi huruf --}}
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-pie-chart me-1 text-success"></i>Distribusi Huruf Mutu</div>
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-md-7">
                        @foreach (['A', 'B', 'C', 'D', 'E'] as $h)
                            @php
                                $jml = $distribusiHuruf[$h] ?? 0;
                                $tot = max(1, $rekap->count());
                                $pct = round(($jml / $tot) * 100);
                            @endphp
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge badge-{{ strtolower($h) }}"
                                    style="width:28px">{{ $h }}</span>
                                <div class="flex-grow-1">
                                    <div class="progress" style="height:10px;border-radius:6px">
                                        <div class="progress-bar badge-{{ strtolower($h) }}"
                                            style="width:{{ $pct }}%"></div>
                                    </div>
                                </div>
                                <span class="text-muted"
                                    style="font-size:12px;width:48px;text-align:right">{{ $jml }}
                                    mhs</span>
                                <span class="text-muted" style="font-size:12px;width:36px">{{ $pct }}%</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-md-5">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="card text-center py-2 border-success">
                                    <div class="fw-700 text-success">{{ $lulus }}</div>
                                    <div class="text-muted" style="font-size:11px">Lulus</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card text-center py-2 border-danger">
                                    <div class="fw-700 text-danger">{{ $tl }}</div>
                                    <div class="text-muted" style="font-size:11px">Tidak Lulus</div>
                                </div>
                            </div>
                        </div>
                        @if ($totalMhs > 0)
                            <div class="mt-2 text-center"><span
                                    class="badge {{ round(($lulus / $totalMhs) * 100) >= 75 ? 'bg-success' : 'bg-warning text-dark' }} fs-6 px-3">{{ round(($lulus / $totalMhs) * 100) }}%
                                    Lulus</span></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="bi bi-table text-primary me-1"></i>Detail Nilai</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0" style="font-size:13px">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Mahasiswa</th>
                            <th class="text-center">Teori</th>
                            <th class="text-center">Prak</th>
                            <th class="text-center">NA</th>
                            <th class="text-center">Mutu</th>
                            <th class="text-center">Kehadiran</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekap->sortByDesc('nilai_akhir') as $i => $na)
                            <tr>
                                <td class="ps-3 text-muted">{{ $i + 1 }}</td>
                                <td>
                                    <div class="fw-500">{{ $na->mahasiswa->nama }}</div><code
                                        style="font-size:11px;color:#64748b">{{ $na->mahasiswa->nim }}</code>
                                </td>
                                <td class="text-center">{{ $na->nilai_teori > 0 ? $na->nilai_teori : '—' }}</td>
                                <td class="text-center">{{ $na->nilai_praktikum > 0 ? $na->nilai_praktikum : '—' }}</td>
                                <td class="text-center fw-700 fs-6">{{ $na->nilai_akhir }}</td>
                                <td class="text-center">
                                    @if ($na->huruf_mutu)
                                        <span
                                        class="badge badge-{{ strtolower($na->huruf_mutu) }}">{{ $na->huruf_mutu }}</span>@else—
                                    @endif
                                </td>
                                <td class="text-center"><span
                                        class="badge {{ $na->persentase_kehadiran >= 75 ? 'bg-success' : 'bg-danger' }}">{{ $na->persentase_kehadiran }}%</span>
                                </td>
                                <td class="text-center">
                                    @if ($na->status_kelulusan == 'lulus')
                                        <span class="badge bg-success">Lulus</span>
                                    @elseif($na->status_kelulusan == 'tidak_lulus')
                                        <span class="badge bg-danger" title="{{ $na->keterangan_gagal }}">Tidak
                                            Lulus</span>
                                    @else<span class="badge bg-secondary">Belum</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada data nilai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endsection
