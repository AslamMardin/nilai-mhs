@extends('layouts.app')
@section('title', 'Rekap Nilai')
@section('page-title', 'Nilai · ' . $mataKuliah->nama)
@section('content')

    {{-- Header --}}
    <div class="alert alert-warning py-2 mb-3" style="font-size:13px">
        <i class="bi bi-exclamation-triangle me-1"></i>
        <strong>Perhatian:</strong> Jika Anda melakukan perubahan pada <b>absensi</b>,
        maka hasil kelulusan <b>tidak otomatis diperbarui</b>.
        Silakan lakukan <b>simpan ulang nilai (teori/praktikum)</b> agar status nilai akhir diperbarui.
    </div>
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

                    @if ($mataKuliah->hasTeori())
                        <a href="{{ route('nilai.form-teori', $mataKuliah->id) }}" class="btn btn-sm btn-primary"><i
                                class="bi bi-pencil-square me-1"></i>Input Teori</a>
                        <a href="{{ route('nilai.form-keaktifan', $mataKuliah->id) }}"
                            class="btn btn-sm btn-outline-success"><i class="bi bi-ui-checks me-1"></i>Keaktifan</a>
                        <a href="{{ route('nilai.form-tugas', $mataKuliah->id) }}" class="btn btn-sm btn-outline-primary"><i
                                class="bi bi-journal-text me-1"></i>Tugas</a>
                    @endif
                    @if ($mataKuliah->hasPraktikum())
                        <a href="{{ route('nilai.form-praktikum', $mataKuliah->id) }}"
                            class="btn btn-sm btn-outline-success"><i class="bi bi-tools me-1"></i>Input Praktikum</a>
                    @endif

                    {{-- <a href="{{ route('nilai.excel', $mataKuliah->id) }}" class="btn btn-sm btn-outline-success"><i class="bi bi-file-earmark-excel me-1"></i>Excel</a> --}}
                    <a href="{{ route('laporan.rekap-mk', $mataKuliah->id) }}" class="btn btn-sm btn-outline-info"><i
                            class="bi bi-bar-chart me-1"></i>Laporan</a>
                    <a href="{{ route('nilai.pilih') }}" class="btn btn-sm btn-outline-secondary"><i
                            class="bi bi-arrow-left me-1"></i>Kembali</a>
                </div>
            </div>
        </div>
    </div>


    {{-- Info bobot --}}
    <div class="alert alert-light border py-2 mb-3" style="font-size:12px">
        @if ($mataKuliah->hasTeori() && $bobot)
            <strong>Bobot Teori:</strong>
            Keaktifan {{ $bobot->keaktifan }}% +
            Tugas {{ $bobot->tugas }}% +
            UTS {{ $bobot->uts }}% +
            UAS {{ $bobot->uas }}%
        @endif
        @if ($mataKuliah->jenis == 'teori_praktikum')
            &nbsp;|&nbsp; <strong>Nilai Akhir:</strong> Teori 50% + Praktikum 50%
        @elseif($mataKuliah->jenis == 'praktikum')
            <strong>Bobot:</strong> Praktikum 100%
        @endif
        &nbsp;|&nbsp; <strong>Syarat Lulus:</strong> Kehadiran ≥ 75% <strong>DAN</strong> Nilai Akhir ≥ 55
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
                            <th class="ps-3" rowspan="2">#</th>
                            <th rowspan="2">NIM / Nama</th>
                            @if ($mataKuliah->hasTeori())
                                <th colspan="5" class="text-center border-start" style="background:#f0f7ff">Nilai Teori
                                </th>
                            @endif
                            @if ($mataKuliah->hasPraktikum())
                                <th colspan="1" class="text-center border-start" style="background:#f0fff4">Praktikum
                                </th>
                            @endif
                            <th colspan="3" class="text-center border-start" style="background:#fafaf0">Kehadiran</th>
                            <th colspan="3" class="text-center border-start" style="background:#fff0f0">Hasil Akhir</th>
                        </tr>
                        <tr>
                            @if ($mataKuliah->hasTeori())
                                <th class="text-center border-start" style="font-size:10px;background:#f0f7ff">Aktif</th>
                                <th class="text-center" style="font-size:10px;background:#f0f7ff">Tugas</th>
                                <th class="text-center" style="font-size:10px;background:#f0f7ff">UTS</th>
                                <th class="text-center" style="font-size:10px;background:#f0f7ff">UAS</th>
                                <th class="text-center" style="font-size:10px;background:#f0f7ff">NA Teori</th>
                            @endif
                            @if ($mataKuliah->hasPraktikum())
                                <th class="text-center border-start" style="font-size:10px;background:#f0fff4">Nilai Prak
                                </th>
                            @endif
                            <th class="text-center border-start" style="font-size:10px;background:#fafaf0">Poin</th>
                            <th class="text-center" style="font-size:10px;background:#fafaf0">%</th>
                            <th class="text-center" style="font-size:10px;background:#fafaf0">Ket</th>
                            <th class="text-center border-start fw-700" style="font-size:10px;background:#fff0f0">NA</th>
                            <th class="text-center" style="font-size:10px;background:#fff0f0">Mutu</th>
                            <th class="text-center" style="font-size:10px;background:#fff0f0">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mahasiswaList as $idx => $mhs)
                            @php
                                $nt = $mhs->nilaiTeori->first();
                                $np = $mhs->nilaiPraktikum->first();
                                $na = $mhs->nilaiAkhir->first();
                            @endphp
                            <tr>
                                <td class="ps-3 text-muted">{{ $idx + 1 }}</td>
                                <td>
                                    <a href="{{ route('mahasiswa.show', $mhs->id) }}"
                                        class="text-decoration-none text-dark">
                                        <div class="fw-500">{{ $mhs->nama }}</div>
                                        <code style="font-size:11px;color:#64748b">{{ $mhs->nim }}</code>
                                    </a>
                                </td>
                                @if ($mataKuliah->hasTeori())
                                    <td class="text-center border-start">{{ $nt?->keaktifan ?? '—' }}</td>
                                    <td class="text-center">{{ $nt?->tugas ?? '—' }}</td>
                                    <td class="text-center">{{ $nt?->uts ?? '—' }}</td>
                                    <td class="text-center">{{ $nt?->uas ?? '—' }}</td>
                                    <td class="text-center fw-600">{{ $nt?->nilai_akhir_teori ?? '—' }}</td>
                                @endif
                                @if ($mataKuliah->hasPraktikum())
                                    <td class="text-center border-start fw-600">{{ $np?->nilai_praktikum ?? '—' }}</td>
                                @endif
                                <td class="text-center border-start">{{ $na?->poin_kehadiran ?? '—' }}</td>
                                <td class="text-center">
                                    @if ($na)
                                        <span
                                            class="badge {{ $na->persentase_kehadiran >= 75 ? 'bg-success' : 'bg-danger' }}"
                                            style="font-size:11px">{{ $na->persentase_kehadiran }}%</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($na)
                                        @if ($na->persentase_kehadiran >= 75)
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                        @else<i class="bi bi-x-circle-fill text-danger" title="Kehadiran < 75%"></i>
                                        @endif
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="text-center border-start fw-700" style="font-size:16px">
                                    {{ $na?->nilai_akhir ?? '—' }}</td>
                                <td class="text-center">
                                    @if ($na?->huruf_mutu)
                                        <span
                                            class="badge badge-{{ strtolower($na->huruf_mutu) }} px-2">{{ $na->huruf_mutu }}</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($na)
                                        @if ($na->status_kelulusan == 'lulus')
                                            <span class="badge bg-success">Lulus</span>
                                        @elseif($na->status_kelulusan == 'tidak_lulus')
                                            <span class="badge bg-danger" title="{{ $na->keterangan_gagal }}">Tidak
                                                Lulus</span>
                                        @else<span class="badge bg-secondary">Belum</span>
                                        @endif
                                    @else<span class="badge bg-light text-muted border"
                                            style="font-size:11px">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="15" class="text-center text-muted py-4">Belum ada mahasiswa terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
