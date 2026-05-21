@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('content')
    @php use Illuminate\Support\Str; @endphp

    <style>
        /* Card Hover Animations & Shadows */
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
        }

        .sc {
            border-radius: 12px;
            padding: 20px;
            color: #fff;
            position: relative;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -1px rgba(0, 0, 0, 0.04);
            cursor: default;
        }

        .sc:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.15), 0 4px 6px -2px rgba(0, 0, 0, 0.08);
        }

        .sc-num {
            font-size: 32px;
            font-weight: 700;
            line-height: 1;
        }

        .sc-lbl {
            font-size: 12px;
            opacity: .85;
            margin-top: 5px;
            font-weight: 500;
            letter-spacing: .02em;
        }

        .sc-ico {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 46px;
            opacity: .25;
            transition: transform 0.3s ease;
        }

        .sc:hover .sc-ico {
            transform: translateY(-50%) scale(1.1) rotate(5deg);
        }

        /* Tabs Styling */
        .card-header-tabs .nav-link {
            color: #64748b;
            border-radius: 6px 6px 0 0;
            transition: all 0.2s ease;
            font-weight: 600;
            border: none !important;
            padding: 10px 16px;
        }

        .card-header-tabs .nav-link:hover {
            background-color: #f1f5f9;
            color: #1e293b;
        }

        .card-header-tabs .nav-link.active {
            background-color: #fff !important;
            border-bottom: 3px solid #1a4a7a !important;
            color: #1a4a7a !important;
        }
    </style>

    {{-- Hero Info Kampus --}}
    <div class="d-flex align-items-center gap-3 mb-4 p-4 rounded-3 shadow-sm"
        style="background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: #fff;">
        <div
            style="width: 56px; height: 56px; border-radius: 14px; background: rgba(255,255,255,.18); display: flex; align-items: center; justify-content: center; font-size: 26px; flex-shrink: 0;">
            <i class="bi bi-building"></i>
        </div>
        <div class="flex-grow-1">
            <div class="fw-700 fs-5 mb-1">{{ $kampusAktif?->nama ?? 'Semua Kampus' }}</div>
            <div style="opacity: .85; font-size: 13px;">
                <i class="bi bi-geo-alt me-1"></i>{{ $kampusAktif?->kode }}
                @if ($kampusAktif?->alamat)
                    · {{ $kampusAktif->alamat }}
                @endif
            </div>
        </div>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-light dropdown-toggle px-3 py-2" data-bs-toggle="dropdown"
                style="border-radius: 20px; font-weight: 500;">
                <i class="bi bi-arrow-left-right me-1"></i>Ganti Kampus
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2" style="border-radius: 12px;">
                <li class="dropdown-header fw-bold text-dark mb-1" style="font-size: 12px;">PILIH KAMPUS</li>
                @foreach ($semuaKampus as $k)
                    <li>
                        <form method="POST" action="{{ route('ganti-kampus') }}">
                            @csrf
                            <input type="hidden" name="kampus_id" value="{{ $k->id }}">
                            <button type="submit"
                                class="dropdown-item d-flex align-items-center gap-2 py-2 {{ $kampusAktif?->id == $k->id ? 'active' : '' }}">
                                <i class="bi bi-building-check"></i>
                                <span>
                                    <strong>{{ $k->kode }}</strong> — <span
                                        class="small text-muted">{{ Str::limit($k->nama, 25) }}</span>
                                </span>
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Aksi Cepat Widget --}}
    <div class="card mb-4 border-0 shadow-sm" style="background: #f8fafc;">
        <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2 overflow-x-auto">
                <span class="fw-700 text-muted small text-uppercase me-2"
                    style="letter-spacing: .05em; white-space: nowrap;"><i
                        class="bi bi-lightning-charge-fill text-warning me-1"></i>Aksi Cepat:</span>
                <a href="{{ route('absensi.pilih') }}"
                    class="btn btn-sm btn-white d-flex align-items-center gap-1 py-1 px-3 border bg-white text-dark shadow-sm"
                    style="white-space: nowrap; border-radius: 20px;">
                    <i class="bi bi-calendar-check text-primary"></i> Mulai Absensi
                </a>
                <a href="{{ route('nilai.pilih') }}"
                    class="btn btn-sm btn-white d-flex align-items-center gap-1 py-1 px-3 border bg-white text-dark shadow-sm"
                    style="white-space: nowrap; border-radius: 20px;">
                    <i class="bi bi-clipboard-data text-success"></i> Input Nilai
                </a>
                <a href="{{ route('laporan.nilai-kelas') }}"
                    class="btn btn-sm btn-white d-flex align-items-center gap-1 py-1 px-3 border bg-white text-dark shadow-sm"
                    style="white-space: nowrap; border-radius: 20px;">
                    <i class="bi bi-table text-info"></i> Laporan Kelas
                </a>
                <a href="{{ route('backup.index') }}"
                    class="btn btn-sm btn-white d-flex align-items-center gap-1 py-1 px-3 border bg-white text-dark shadow-sm"
                    style="white-space: nowrap; border-radius: 20px;">
                    <i class="bi bi-database text-secondary"></i> Backup DB
                </a>
            </div>
            <div style="min-width: 250px;">
                <form action="{{ route('mahasiswa.index') }}" method="GET" class="input-group input-group-sm mb-0">
                    <input type="text" name="search" class="form-control" placeholder="Cari Nama/NIM Mahasiswa..."
                        required style="border-radius: 20px 0 0 20px; font-size: 12px; border-color: #cbd5e1;">
                    <button class="btn btn-primary" type="submit"
                        style="border-radius: 0 20px 20px 0; font-size: 12px; padding: 2px 14px;"><i
                            class="bi bi-search"></i></button>
                </form>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="sc" style="background: linear-gradient(135deg, #0d9488, #0f766e)">
                <div class="sc-num">{{ $totalMahasiswa }}</div>
                <div class="sc-lbl">Mahasiswa Aktif</div>
                <i class="bi bi-people sc-ico"></i>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="sc" style="background: linear-gradient(135deg, #2563eb, #1d4ed8)">
                <div class="sc-num">{{ $totalMataKuliah }}</div>
                <div class="sc-lbl">Mata Kuliah</div>
                <i class="bi bi-book sc-ico"></i>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="sc" style="background: linear-gradient(135deg, #d97706, #b45309)">
                <div class="sc-num">{{ $totalKelas }}</div>
                <div class="sc-lbl">Kelas</div>
                <i class="bi bi-door-open sc-ico"></i>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="sc" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9)">
                <div class="sc-num">{{ $persenLulus }}%</div>
                <div class="sc-lbl">Tingkat Kelulusan</div>
                <i class="bi bi-patch-check sc-ico"></i>
            </div>
        </div>
    </div>

    {{-- Dashboard Grid Utama --}}
    <div class="row g-3">
        {{-- KIRI: Selebihnya --}}
        <div class="col-lg-8 d-flex flex-column gap-3">
            {{-- Distribusi Huruf Mutu & Rekap Kelas --}}
            <div class="row g-3">
                {{-- Distribusi Huruf Mutu --}}
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center gap-1">
                            <i class="bi bi-pie-chart text-success me-1"></i>Distribusi Huruf Mutu
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                @foreach (['A' => ['success', '#16a34a'], 'B' => ['primary', '#2563eb'], 'C' => ['warning', '#d97706'], 'D' => ['orange', '#ea580c'], 'E' => ['danger', '#dc2626']] as $h => [$cls, $col])
                                    @php
                                        $jml = $distribusi[$h] ?? 0;
                                        $tot = max(1, array_sum($distribusi));
                                        $pct = round(($jml / $tot) * 100);
                                    @endphp
                                    <div class="d-flex align-items-center gap-2 mb-3">
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
                            </div>
                            <div>
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
                </div>

                {{-- Rekap Kelas --}}
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-door-open text-primary me-1"></i>Rekap Kelas</span>
                            <a href="{{ route('kelas.create') }}" class="btn btn-sm btn-outline-primary py-0 px-2">+ Tambah</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3" style="font-size: 10px;">Kelas</th>
                                            <th class="text-center" style="font-size: 10px;">Mahasiswa</th>
                                            <th class="text-center" style="font-size: 10px;">% Lulus</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($kelasList as $kls)
                                            <tr>
                                                <td class="ps-3">
                                                    <span class="fw-600 text-dark">{{ $kls->nama }}</span>
                                                    <div class="text-muted" style="font-size:11px">{{ $kls->kode }} ·
                                                        {{ $kls->semester }} {{ $kls->tahun_ajaran }}</div>
                                                </td>
                                                <td class="text-center align-middle small">{{ $kls->mahasiswa_count }}</td>
                                                <td class="text-center align-middle">
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
                                                <td class="align-middle">
                                                    <a href="{{ route('kelas.show', $kls->id) }}"
                                                        class="btn btn-sm btn-outline-secondary py-0 px-2">Detail</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-3">Belum ada kelas.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Daftar Mata Kuliah --}}
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-book text-warning me-1"></i>Daftar Mata Kuliah</span>
                    <a href="{{ route('matakuliah.create') }}" class="btn btn-sm btn-outline-primary py-0 px-2">+
                        Tambah</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="font-size: 10px;">Kode</th>
                                    <th style="font-size: 10px;">Mata Kuliah</th>
                                    <th style="font-size: 10px;">Kelas</th>
                                    <th class="text-center" style="font-size: 10px;">Jenis</th>
                                    <th class="text-center" style="font-size: 10px;">Mhs</th>
                                    <th class="text-center" style="font-size: 10px;">Dinilai</th>
                                    <th style="font-size: 10px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mataKuliahList as $mk)
                                    <tr>
                                        <td class="ps-3 align-middle"><code class="small">{{ $mk->kode }}</code>
                                        </td>
                                        <td>
                                            <div class="fw-600 text-dark">{{ $mk->nama }}</div>
                                            <div class="text-muted" style="font-size:11px">{{ $mk->sks }}
                                                SKS · {{ $mk->dosen ?? '—' }}</div>
                                        </td>
                                        <td class="align-middle small">{{ $mk->kelas->nama }}</td>
                                        <td class="text-center align-middle">
                                            <span
                                                class="badge {{ $mk->jenis == 'teori' ? 'bg-info text-dark' : ($mk->jenis == 'praktikum' ? 'bg-success' : 'bg-primary') }}">
                                                {{ $mk->label_jenis }}
                                            </span>
                                        </td>
                                        <td class="text-center align-middle small">{{ $mk->mahasiswa_count }}</td>
                                        <td class="text-center align-middle"><span
                                                class="badge bg-secondary">{{ $mk->nilai_akhir_count }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ route('nilai.index', $mk->id) }}"
                                                class="btn btn-sm btn-outline-primary py-0 px-2 me-1"
                                                title="Input Nilai"><i class="bi bi-clipboard-data"></i></a>
                                            <a href="{{ route('absensi.index', $mk->id) }}"
                                                class="btn btn-sm btn-outline-secondary py-0 px-2" title="Absensi"><i
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
            </div>
        </div>

        {{-- KANAN: Ranking & Berisiko --}}
        <div class="col-lg-4 d-flex flex-column gap-3">
            {{-- Kalender Jadwal Mengajar --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-calendar3 text-primary me-2"></i>Jadwal Mengajar</h6>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-sm btn-link p-0 text-muted" id="btn-prev-month"><i class="bi bi-chevron-left"></i></button>
                        <span class="fw-bold" style="font-size:13px;" id="calendar-month-year">...</span>
                        <button class="btn btn-sm btn-link p-0 text-muted" id="btn-next-month"><i class="bi bi-chevron-right"></i></button>
                    </div>
                </div>
                <div class="card-body pt-2 pb-3">
                    <div class="d-grid text-center text-muted fw-bold mb-2" style="grid-template-columns: repeat(7, 1fr); font-size: 11px;">
                        <div>Min</div><div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div>
                    </div>
                    <div class="d-grid text-center gap-1" id="calendar-grid" style="grid-template-columns: repeat(7, 1fr); font-size: 13px;">
                        <!-- JS generated dates -->
                    </div>
                    <hr class="my-2 text-muted">
                    <div id="calendar-details" class="small text-muted" style="min-height: 40px;">
                        <em>Pilih tanggal yang memiliki titik warna untuk melihat jadwal kelas.</em>
                    </div>
                </div>
            </div>

            {{-- Ranking Mahasiswa (Nav Tabs per Kelas) --}}
            <div class="card shadow-sm">
                <div class="card-header p-0 border-bottom-0 bg-light d-flex align-items-center justify-content-between pe-3"
                    style="border-radius: 12px 12px 0 0;">
                    <ul class="nav nav-tabs card-header-tabs ms-2 mt-1 border-0" id="rankingTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active py-2 border-0" id="tab-semua" data-bs-toggle="tab"
                                data-bs-target="#panel-semua" type="button" role="tab" style="font-size: 12px;">
                                Semua
                            </button>
                        </li>
                        @foreach ($kelasList as $kls)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-2 border-0" id="tab-kelas-{{ $kls->id }}"
                                    data-bs-toggle="tab" data-bs-target="#panel-kelas-{{ $kls->id }}"
                                    type="button" role="tab" style="font-size: 12px;">
                                    {{ $kls->nama }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    <span class="text-muted small fw-bold" style="font-size: 11px;"><i
                            class="bi bi-trophy-fill text-warning me-1"></i>RANKING</span>
                </div>
                <div class="card-body p-2 tab-content">
                    {{-- Panel Semua (Global) --}}
                    <div class="tab-pane fade show active" id="panel-semua" role="tabpanel">
                        <table class="table table-sm mb-0">
                            <tbody>
                                @forelse ($rankingMahasiswa as $i => $mhs)
                                    <tr>
                                        <td style="width:35px" class="text-center align-middle">
                                            @if ($i == 0)
                                                🥇
                                            @elseif($i == 1)
                                                🥈
                                            @elseif($i == 2)
                                                🥉
                                            @else
                                                <span class="badge bg-light text-dark border">{{ $i + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-600 text-dark" style="font-size: 13px;">{{ $mhs->nama }}
                                            </div>
                                            <small class="text-muted" style="font-size: 11px;">{{ $mhs->nim }} -
                                                {{ $mhs->kelas->nama }}</small>
                                        </td>
                                        <td class="text-end fw-700 text-primary align-middle" style="font-size: 13px;">
                                            {{ number_format($mhs->rata_nilai ?? 0, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center text-muted py-5">Belum ada data nilai.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Panel per Kelas --}}
                    @foreach ($kelasList as $kls)
                        <div class="tab-pane fade" id="panel-kelas-{{ $kls->id }}" role="tabpanel">
                            <table class="table table-sm mb-0">
                                <tbody>
                                    @forelse ($rankingPerKelas[$kls->id] ?? [] as $i => $mhs)
                                        <tr>
                                            <td style="width:35px" class="text-center align-middle">
                                                @if ($i == 0)
                                                    🥇
                                                @elseif($i == 1)
                                                    🥈
                                                @elseif($i == 2)
                                                    🥉
                                                @else
                                                    <span
                                                        class="badge bg-light text-dark border">{{ $i + 1 }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-600 text-dark" style="font-size: 13px;">
                                                    {{ $mhs->nama }}</div>
                                                <small class="text-muted"
                                                    style="font-size: 11px;">{{ $mhs->nim }}</small>
                                            </td>
                                            <td class="text-end fw-700 text-primary align-middle"
                                                style="font-size: 13px;">
                                                {{ number_format($mhs->rata_nilai ?? 0, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center text-muted py-5">
                                                <i class="bi bi-info-circle fs-3 text-secondary d-block mb-1"></i>
                                                <small class="text-muted">Belum ada data nilai di kelas ini.</small>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEvents = @json($calendarEvents ?? []);
    
    // Group events by date
    const eventsByDate = {};
    calendarEvents.forEach(ev => {
        if(!eventsByDate[ev.date]) eventsByDate[ev.date] = [];
        eventsByDate[ev.date].push(ev);
    });

    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    let currentDate = new Date();

    const renderCalendar = () => {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        document.getElementById('calendar-month-year').textContent = `${monthNames[month]} ${year}`;
        
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        
        const grid = document.getElementById('calendar-grid');
        grid.innerHTML = '';
        
        for(let i = 0; i < firstDay; i++) {
            const empty = document.createElement('div');
            grid.appendChild(empty);
        }
        
        // Handle timezone issues by constructing date string manually from local parts
        const todayObj = new Date();
        const todayStr = `${todayObj.getFullYear()}-${String(todayObj.getMonth()+1).padStart(2,'0')}-${String(todayObj.getDate()).padStart(2,'0')}`;

        for(let i = 1; i <= daysInMonth; i++) {
            const cell = document.createElement('div');
            const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(i).padStart(2,'0')}`;
            
            cell.textContent = i;
            cell.style.padding = '4px 0';
            cell.style.borderRadius = '50%';
            cell.style.cursor = 'pointer';
            cell.style.position = 'relative';
            
            if(dateStr === todayStr) {
                cell.className = 'bg-primary text-white fw-bold';
            }
            
            const hasEvents = eventsByDate[dateStr];
            if(hasEvents) {
                if(dateStr !== todayStr) cell.classList.add('fw-bold', 'text-primary');
                
                const dot = document.createElement('div');
                dot.style.position = 'absolute';
                dot.style.bottom = '2px';
                dot.style.left = '50%';
                dot.style.transform = 'translateX(-50%)';
                dot.style.width = '4px';
                dot.style.height = '4px';
                dot.style.borderRadius = '50%';
                dot.style.backgroundColor = dateStr === todayStr ? '#fff' : '#0d6efd';
                cell.appendChild(dot);
            }

            cell.addEventListener('click', () => {
                const details = document.getElementById('calendar-details');
                if(!hasEvents) {
                    details.innerHTML = `<em>Tidak ada jadwal pada ${i} ${monthNames[month]} ${year}.</em>`;
                    return;
                }
                
                let html = `<div class="fw-bold mb-2 text-dark border-bottom pb-1">Jadwal: ${i} ${monthNames[month]} ${year}</div>`;
                hasEvents.forEach(ev => {
                    html += `<div class="d-flex align-items-start mb-2">
                                <i class="bi bi-clock me-2 text-primary" style="margin-top: 2px;"></i>
                                <div style="line-height: 1.2;">
                                    <div class="fw-bold text-dark" style="font-size: 12px;">${ev.time || 'Waktu belum diatur'}</div>
                                    <div class="text-secondary" style="font-size: 11px;">${ev.title} (Pertemuan ${ev.pertemuan})</div>
                                </div>
                             </div>`;
                });
                details.innerHTML = html;
            });
            
            grid.appendChild(cell);
        }
    };

    renderCalendar();

    document.getElementById('btn-prev-month').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });
    
    document.getElementById('btn-next-month').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });
});
</script>
@endpush
