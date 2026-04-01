<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Penilaian') — ITBM & STAIN Majene</title>

    {{-- Bootstrap 5 --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --sidebar-w: 260px;
            --topbar-h: 60px;
            --color-primary: #1a4a7a;
            --color-accent:  #e8a020;
        }

        body { background: #f4f6f9; font-size: 14px; }

        /* ── Sidebar ───────────────────────────── */
        #sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w); height: 100vh;
            background: var(--color-primary);
            overflow-y: auto; z-index: 1000;
            transition: transform .25s ease;
        }
        #sidebar .brand {
            padding: 18px 20px 14px;
            border-bottom: 1px solid rgba(255,255,255,.12);
        }
        #sidebar .brand h6 { color: #fff; font-weight: 700; margin: 0; font-size: 13px; }
        #sidebar .brand small { color: rgba(255,255,255,.55); font-size: 11px; }

        #sidebar .nav-label {
            color: rgba(255,255,255,.4);
            font-size: 10px; font-weight: 600;
            letter-spacing: .08em; text-transform: uppercase;
            padding: 14px 20px 4px;
        }
        #sidebar .nav-link {
            color: rgba(255,255,255,.75);
            padding: 9px 20px; display: flex; align-items: center; gap: 10px;
            border-radius: 0; font-size: 13px;
            transition: background .15s, color .15s;
        }
        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            background: rgba(255,255,255,.1);
            color: #fff;
        }
        #sidebar .nav-link.active { border-left: 3px solid var(--color-accent); }
        #sidebar .nav-link i { font-size: 15px; width: 18px; text-align: center; }

        /* ── Topbar ────────────────────────────── */
        #topbar {
            position: fixed; top: 0;
            left: var(--sidebar-w); right: 0;
            height: var(--topbar-h);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center;
            padding: 0 24px; gap: 16px; z-index: 999;
        }
        #topbar .page-title { font-weight: 600; font-size: 15px; color: #1e293b; }
        #topbar .user-badge {
            margin-left: auto;
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; color: #475569;
        }
        #topbar .avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: var(--color-primary);
            color: #fff; font-size: 13px; font-weight: 600;
            display: flex; align-items: center; justify-content: center;
        }

        /* ── Main Content ──────────────────────── */
        #main {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100vh;
        }
        .content-wrap { padding: 24px; }

        /* ── Cards ─────────────────────────────── */
        .card { border: 1px solid #e2e8f0; border-radius: 10px; box-shadow: none; }
        .card-header { background: #fff; border-bottom: 1px solid #e2e8f0; font-weight: 600; }

        /* ── Stat Cards ─────────────────────────── */
        .stat-card { border-radius: 10px; padding: 20px; color: #fff; }
        .stat-card .stat-num { font-size: 28px; font-weight: 700; line-height: 1; }
        .stat-card .stat-label { font-size: 12px; opacity: .85; margin-top: 4px; }
        .stat-card .stat-icon { font-size: 36px; opacity: .3; }

        /* ── Badge Nilai ─────────────────────────── */
        .badge-A { background: #16a34a; color: #fff; }
        .badge-B { background: #2563eb; color: #fff; }
        .badge-C { background: #d97706; color: #fff; }
        .badge-D { background: #ea580c; color: #fff; }
        .badge-E { background: #dc2626; color: #fff; }

        /* ── Tabel ──────────────────────────────── */
        .table th { font-size: 12px; text-transform: uppercase;
                    letter-spacing: .04em; color: #64748b; font-weight: 600; }
        .table td { vertical-align: middle; }

        /* ── Alert flash ────────────────────────── */
        .alert { border-radius: 8px; font-size: 13px; }

        /* ── Absensi status pills ───────────────── */
        .pill-H { background:#dcfce7; color:#166534; }
        .pill-T { background:#fef9c3; color:#854d0e; }
        .pill-S { background:#dbeafe; color:#1e40af; }
        .pill-I { background:#f1f5f9; color:#475569; }
        .pill-A { background:#fee2e2; color:#991b1b; }
        .status-pill { display:inline-block; padding:2px 8px; border-radius:20px;
                        font-size:11px; font-weight:600; }

        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #topbar, #main { left: 0; margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ═══ SIDEBAR ═══════════════════════════════════════════════ --}}
<nav id="sidebar">
    <div class="brand">
        <h6><i class="bi bi-mortarboard-fill me-1"></i> Sistem Penilaian</h6>
        <small>ITBM & STAIN Majene</small>
    </div>

    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('dashboard') }}"
       class="nav-link @active('dashboard')">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <div class="nav-label">Master Data</div>
    <a href="{{ route('kampus.index') }}"
       class="nav-link @active('kampus*')">
        <i class="bi bi-building"></i> Kampus
    </a>
    <a href="{{ route('kelas.index') }}"
       class="nav-link @active('kelas*')">
        <i class="bi bi-door-open"></i> Kelas
    </a>
    <a href="{{ route('mata-kuliah.index') }}"
       class="nav-link @active('mata-kuliah*')">
        <i class="bi bi-book"></i> Mata Kuliah
    </a>
    <a href="{{ route('mahasiswa.index') }}"
       class="nav-link @active('mahasiswa*')">
        <i class="bi bi-people"></i> Mahasiswa
    </a>

    <div class="nav-label">Akademik</div>
    <a href="{{ route('absensi.index',1) }}"
       class="nav-link @active('absensi*')">
        <i class="bi bi-calendar-check"></i> Absensi
    </a>
    <a href="{{ route('nilai.index',1) }}"
       class="nav-link @active('nilai*')">
        <i class="bi bi-clipboard-data"></i> Nilai
    </a>

    <div class="nav-label">Laporan</div>
    <a href="{{ route('laporan.nilai-per-kelas') }}"
       class="nav-link @active('laporan.nilai-per-kelas')">
        <i class="bi bi-table"></i> Nilai per Kelas
    </a>
    <a href="{{ route('laporan.rekap-kampus') }}"
       class="nav-link @active('laporan.rekap-kampus')">
        <i class="bi bi-bar-chart-line"></i> Rekap Kampus
    </a>
    <a href="{{ route('laporan.transkrip') }}"
       class="nav-link @active('laporan.transkrip')">
        <i class="bi bi-file-earmark-text"></i> Transkrip
    </a>

    <div class="nav-label">Akun</div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
            <i class="bi bi-box-arrow-left"></i> Keluar
        </button>
    </form>
</nav>

{{-- ═══ TOPBAR ═══════════════════════════════════════════════ --}}
<header id="topbar">
    <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>
    <span class="page-title">@yield('page-title', 'Dashboard')</span>

    {{-- Breadcrumb --}}
    @hasSection('breadcrumb')
    <nav aria-label="breadcrumb" class="d-none d-md-block">
        <ol class="breadcrumb mb-0 small">
            @yield('breadcrumb')
        </ol>
    </nav>
    @endif

    <div class="user-badge">
        <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
    </div>
</header>

{{-- ═══ MAIN ═════════════════════════════════════════════════ --}}
<main id="main">
    <div class="content-wrap">

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-1"></i>
            <strong>Terdapat kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>
</main>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar mobile toggle
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('open');
    });

    // Auto-highlight active nav link
    document.querySelectorAll('#sidebar .nav-link').forEach(link => {
        if (link.href && window.location.pathname.startsWith(new URL(link.href).pathname)) {
            link.classList.add('active');
        }
    });
</script>
@stack('scripts')
</body>
</html>
