@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('content')
    @php use Illuminate\Support\Str; @endphp

    {{-- Hero Info Kampus --}}
    <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3"
        style="background:linear-gradient(135deg,#1a4a7a,#2563eb);color:#fff">
        <div
            style="width:52px;height:52px;border-radius:12px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">
            <i class="bi bi-building"></i>
        </div>
        <div class="flex-grow-1">
            <div class="fw-700 fs-5">{{ $kampusAktif?->nama ?? 'Semua Kampus' }}</div>
            <div style="opacity:.8;font-size:13px">{{ $kampusAktif?->kode }} @if ($kampusAktif?->alamat)
                    · {{ $kampusAktif->alamat }}
                @endif
            </div>
        </div>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-light dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-arrow-left-right me-1"></i>Ganti Kampus
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow">
                @foreach ($semuaKampus as $k)
                    <li>
                        <form method="POST" action="{{ route('ganti-kampus') }}">
                            @csrf<input type="hidden" name="kampus_id" value="{{ $k->id }}">
                            <button type="submit" class="dropdown-item {{ $kampusAktif?->id == $k->id ? 'active' : '' }}">
                                <strong>{{ $k->kode }}</strong> — {{ Str::limit($k->nama, 30) }}
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="sc" style="background:#0f766e">
                <div class="sc-num">{{ $totalMahasiswa }}</div>
                <div class="sc-lbl">Mahasiswa Aktif</div>
                <i class="bi bi-people sc-ico"></i>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="sc" style="background:#1a4a7a">
                <div class="sc-num">{{ $totalMataKuliah }}</div>
                <div class="sc-lbl">Mata Kuliah</div>
                <i class="bi bi-book sc-ico"></i>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="sc" style="background:#b45309">
                <div class="sc-num">{{ $totalKelas }}</div>
                <div class="sc-lbl">Kelas</div>
                <i class="bi bi-door-open sc-ico"></i>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="sc" style="background:#7c3aed">
                <div class="sc-num">{{ $persenLulus }}%</div>
                <div class="sc-lbl">Tingkat Kelulusan</div>
                <i class="bi bi-patch-check sc-ico"></i>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        {{-- Distribusi Huruf Mutu --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-pie-chart text-success me-1"></i>Distribusi Huruf Mutu</div>
                <div class="card-body">
                    @foreach (['A' => ['success', '#16a34a'], 'B' => ['primary', '#2563eb'], 'C' => ['warning', '#d97706'], 'D' => ['orange', '#ea580c'], 'E' => ['danger', '#dc2626']] as $h => [$cls, $col])
                        @php
                            $jml = $distribusi[$h] ?? 0;
                            $tot = max(1, array_sum($distribusi));
                            $pct = round(($jml / $tot) * 100);
                        @endphp
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge badge-{{ strtolower($h) }} px-2"
                                style="width:26px">{{ $h }}</span>
                            <div class="flex-grow-1">
                                <div class="progress" style="height:9px;border-radius:6px">
                                    <div class="progress-bar"
                                        style="width:{{ $pct }}%;background:{{ $col }}"></div>
                                </div>
                            </div>
                            <span class="text-muted"
                                style="font-size:11px;width:40px;text-align:right">{{ $jml }}</span>
                        </div>
                    @endforeach
                    <hr class="my-3">
                    <div class="row text-center g-2">
                        <div class="col-6">
                            <div class="rounded-2 p-2" style="background:#f0fdf4">
                                <div class="fw-700 text-success fs-5">{{ $statLulus['lulus'] }}</div>
                                <div class="text-muted" style="font-size:11px">Lulus</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="rounded-2 p-2" style="background:#fff1f2">
                                <div class="fw-700 text-danger fs-5">{{ $statLulus['tidak_lulus'] }}</div>
                                <div class="text-muted" style="font-size:11px">Tidak Lulus</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- rangking --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    Ranking Mahasiswa
                </div>
                <div class="card-body p-2">
                    <table class="table table-sm mb-0">
                        <tbody>
                            @foreach ($rankingMahasiswa as $i => $mhs)
                                <tr>
                                    <td style="width:40px" class="text-center">
                                        @if ($i == 0)
                                            🥇
                                        @elseif($i == 1)
                                            🥈
                                        @elseif($i == 2)
                                            🥉
                                        @else
                                            {{ $i + 1 }}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-500">{{ $mhs->nama }}</div>
                                        <small class="text-muted">{{ $mhs->nim }} - {{ $mhs->kelas->nama }}</small>
                                    </td>
                                    <td class="text-end fw-700 text-primary">
                                        {{ number_format($mhs->rata_nilai ?? 0, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Rekap Kelas --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <span><i class="bi bi-door-open text-primary me-1"></i>Rekap Kelas</span>
                    <a href="{{ route('kelas.create') }}" class="btn btn-sm btn-outline-primary py-0">+ Tambah</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Kelas</th>
                                <th class="text-center">Mahasiswa</th>
                                <th class="text-center">% Lulus</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kelasList as $kls)
                                <tr>
                                    <td class="ps-3">
                                        <span class="fw-500">{{ $kls->nama }}</span>
                                        <div class="text-muted" style="font-size:11px">{{ $kls->kode }} ·
                                            {{ $kls->semester }} {{ $kls->tahun_ajaran }}</div>
                                    </td>
                                    <td class="text-center">{{ $kls->mahasiswa_count }}</td>
                                    <td class="text-center">
                                        {{-- @php $r=$rekapKelas->firstWhere(fn($x)=>$x['kelas']->id==$kls->id); @endphp --}}
                                        @php
                                            $r = $rekapKelas->firstWhere('kelas_id', $kls->id);
                                        @endphp
                                        @if ($r)
                                            <span
                                                class="badge 
        {{ $r['pct_lulus'] >= 80 ? 'bg-success' : ($r['pct_lulus'] >= 60 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                                {{ $r['pct_lulus'] }}%
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('kelas.show', $kls->id) }}"
                                            class="btn btn-sm btn-outline-secondary py-0 px-2">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Belum ada kelas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- Mata Kuliah Terbaru --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <span><i class="bi bi-book text-warning me-1"></i>Mata Kuliah</span>
            <a href="{{ route('matakuliah.create') }}" class="btn btn-sm btn-outline-primary py-0">+ Tambah</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Kode</th>
                        <th>Mata Kuliah</th>
                        <th>Kelas</th>
                        <th class="text-center">Jenis</th>
                        <th class="text-center">Mahasiswa</th>
                        <th class="text-center">Dinilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mataKuliahList as $mk)
                        <tr>
                            <td class="ps-3"><code class="small">{{ $mk->kode }}</code></td>
                            <td>{{ $mk->nama }}<div class="text-muted" style="font-size:11px">{{ $mk->sks }}
                                    SKS · {{ $mk->dosen ?? '—' }}</div>
                            </td>
                            <td>{{ $mk->kelas->nama }}</td>
                            <td class="text-center">
                                <span
                                    class="badge {{ $mk->jenis == 'teori' ? 'bg-info text-dark' : ($mk->jenis == 'praktikum' ? 'bg-success' : 'bg-primary') }}">
                                    {{ $mk->label_jenis }}
                                </span>
                            </td>
                            <td class="text-center">{{ $mk->mahasiswa_count }}</td>
                            <td class="text-center"><span class="badge bg-secondary">{{ $mk->nilai_akhir_count }}</span>
                            </td>
                            <td>
                                <a href="{{ route('nilai.index', $mk->id) }}"
                                    class="btn btn-sm btn-outline-primary py-0 px-2 me-1"><i
                                        class="bi bi-clipboard-data"></i></a>
                                <a href="{{ route('absensi.index', $mk->id) }}"
                                    class="btn btn-sm btn-outline-secondary py-0 px-2"><i
                                        class="bi bi-calendar-check"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">Belum ada mata kuliah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
