<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Beranda') — Sistem Penilaian</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
:root{--sb:256px;--tb:60px;--pri:#1a4a7a;--acc:#e8a020}
body{background:#f1f4f8;font-size:14px;font-family:'Segoe UI',system-ui,sans-serif}
/* SIDEBAR */
#sb{position:fixed;top:0;left:0;width:var(--sb);height:100vh;background:var(--pri);overflow-y:auto;z-index:1050;transition:transform .25s}
#sb .brand{padding:16px 20px 14px;border-bottom:1px solid rgba(255,255,255,.1)}
#sb .brand-title{color:#fff;font-weight:700;font-size:14px;margin:0;line-height:1.3}
#sb .brand-sub{color:rgba(255,255,255,.5);font-size:11px}
#sb .nav-section{color:rgba(255,255,255,.35);font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;padding:14px 20px 4px}
#sb a.nl{display:flex;align-items:center;gap:10px;padding:9px 20px;color:rgba(255,255,255,.75);text-decoration:none;font-size:13px;transition:background .15s,color .15s;border-left:3px solid transparent}
#sb a.nl:hover{background:rgba(255,255,255,.08);color:#fff}
#sb a.nl.active{background:rgba(255,255,255,.12);color:#fff;border-left-color:var(--acc)}
#sb a.nl i{width:18px;text-align:center;font-size:15px}
/* TOPBAR */
#tb{position:fixed;top:0;left:var(--sb);right:0;height:var(--tb);background:#fff;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;padding:0 20px;gap:12px;z-index:1040}
#tb .pg-title{font-weight:600;font-size:15px;color:#1e293b;flex:1}
/* Kampus badge in topbar */
.kampus-badge{display:flex;align-items:center;gap:6px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:20px;padding:4px 12px;font-size:12px;color:#1d4ed8;font-weight:500;white-space:nowrap}
.kampus-badge i{font-size:13px}
/* MAIN */
#main{margin-left:var(--sb);padding-top:var(--tb);min-height:100vh}
.cw{padding:24px}
/* CARDS */
.card{border:1px solid #e2e8f0;border-radius:10px;box-shadow:none}
.card-header{background:#fff;border-bottom:1px solid #f1f5f9;font-weight:600;font-size:13px;padding:12px 16px}
/* STAT CARDS */
.sc{border-radius:12px;padding:20px;color:#fff;position:relative;overflow:hidden}
.sc-num{font-size:30px;font-weight:700;line-height:1}
.sc-lbl{font-size:12px;opacity:.85;margin-top:5px}
.sc-ico{position:absolute;right:16px;top:50%;transform:translateY(-50%);font-size:42px;opacity:.2}
/* BADGES NILAI */
.badge-a{background:#16a34a!important;color:#fff}
.badge-b{background:#2563eb!important;color:#fff}
.badge-c{background:#d97706!important;color:#fff}
.badge-d{background:#ea580c!important;color:#fff}
.badge-e{background:#dc2626!important;color:#fff}
/* TABLE */
.table th{font-size:11px;text-transform:uppercase;letter-spacing:.04em;color:#64748b;font-weight:600;white-space:nowrap}
.table td{vertical-align:middle}
/* STATUS ABSENSI */
.sp-H{background:#dcfce7;color:#166534}
.sp-T{background:#fef9c3;color:#854d0e}
.sp-S{background:#dbeafe;color:#1e40af}
.sp-I{background:#f1f5f9;color:#475569}
.sp-A{background:#fee2e2;color:#991b1b}
.sp{display:inline-flex;align-items:center;justify-content:center;width:28px;height:22px;border-radius:4px;font-size:11px;font-weight:700}
/* ALERTS */
.alert{border-radius:8px;font-size:13px}
/* FORMS */
.form-label{font-weight:500;font-size:13px;color:#374151}
.form-control,.form-select{border-radius:8px;font-size:14px}
.form-control:focus,.form-select:focus{box-shadow:0 0 0 3px rgba(26,74,122,.12);border-color:#1a4a7a}
/* BUTTONS */
.btn{border-radius:7px;font-size:13px}
.btn-primary{background:var(--pri);border-color:var(--pri)}
.btn-primary:hover{background:#153d66;border-color:#153d66}
/* SECTION HEADER */
.sh{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px}
.sh h5{margin:0;font-size:16px;font-weight:600}
/* MOBILE */
@media(max-width:768px){
  #sb{transform:translateX(-100%)}
  #sb.open{transform:translateX(0)}
  #tb,#main{left:0;margin-left:0}
  #tb{left:0}
}
</style>
@stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<nav id="sb">
  <div class="brand">
    <p class="brand-title"><i class="bi bi-mortarboard-fill me-1"></i>Sistem Penilaian</p>
    <p class="brand-sub mb-0">
  {{ auth()->user()->namalengkap ?? auth()->user()->name }}
</p>
  </div>

  <div class="nav-section">Utama</div>
  <a href="{{ route('dashboard') }}" class="nl {{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> Dashboard
  </a>

  <div class="nav-section">Master Data</div>
  <a href="{{ route('kampus.index') }}" class="nl {{ request()->routeIs('kampus*') ? 'active' : '' }}">
    <i class="bi bi-building"></i> Kampus
  </a>
  <a href="{{ route('kelas.index') }}" class="nl {{ request()->routeIs('kelas*') ? 'active' : '' }}">
    <i class="bi bi-door-open"></i> Kelas
  </a>
  <a href="{{ route('matakuliah.index') }}" class="nl {{ request()->routeIs('matakuliah*') ? 'active' : '' }}">
    <i class="bi bi-book"></i> Mata Kuliah
  </a>
  <a href="{{ route('mahasiswa.index') }}" class="nl {{ request()->routeIs('mahasiswa*') ? 'active' : '' }}">
    <i class="bi bi-people"></i> Mahasiswa
  </a>

  <div class="nav-section">Akademik</div>
  <a href="{{ route('absensi.pilih') }}" class="nl {{ request()->routeIs('absensi*') ? 'active' : '' }}">
    <i class="bi bi-calendar-check"></i> Absensi
  </a>
  <a href="{{ route('nilai.pilih') }}" class="nl {{ request()->routeIs('nilai*') ? 'active' : '' }}">
    <i class="bi bi-clipboard-data"></i> Nilai
  </a>

  <div class="nav-section">Laporan</div>
  <a href="{{ route('laporan.nilai-kelas') }}" class="nl {{ request()->routeIs('laporan.nilai-kelas') ? 'active' : '' }}">
    <i class="bi bi-table"></i> Nilai per Kelas
  </a>
  <a href="{{ route('laporan.rekap') }}" class="nl {{ request()->routeIs('laporan.rekap') ? 'active' : '' }}">
    <i class="bi bi-bar-chart-line"></i> Rekap Kampus
  </a>
  <a href="{{ route('laporan.transkrip') }}" class="nl {{ request()->routeIs('laporan.transkrip') ? 'active' : '' }}">
    <i class="bi bi-file-earmark-text"></i> Transkrip
  </a>

  

  <div class="nav-section">Pengaturan</div>
    <a href="{{ route('backup.index') }}" class="nl {{ request()->routeIs('backup.index') ? 'active' : '' }}">
    <i class="bi bi-file-earmark-text"></i> Database
  </a>
  <a href="{{ route('bobot.index') }}" class="nl {{ request()->routeIs('bobot.index') ? 'active' : '' }}">
    <i class="bi bi-sliders me-1"></i> Bobot Nilai
  </a>
    <a href="{{ route('profile.edit') }}" class="nl {{ request()->routeIs('profile.*') ? 'active' : '' }}">
  <i class="bi bi-person"></i> Edit Profil
</a>

<a href="{{ route('password.edit') }}" class="nl {{ request()->routeIs('password.*') ? 'active' : '' }}">
  <i class="bi bi-lock"></i> Ganti Password
</a>
</nav>

{{-- TOPBAR --}}
<header id="tb">
  <button class="btn btn-sm btn-light d-lg-none me-1" id="sbt"><i class="bi bi-list fs-5"></i></button>
  <span class="pg-title">@yield('page-title','Dashboard')</span>

  {{-- Ganti Kampus dropdown --}}
  @php $kampusAktif = App\Models\Kampus::find(session('kampus_id') ?? auth()->user()?->kampus_id); @endphp
  <div class="dropdown">
    <button class="kampus-badge dropdown-toggle border-0 bg-transparent" data-bs-toggle="dropdown">
      <i class="bi bi-building"></i>
      {{ $kampusAktif?->kode ?? 'Pilih Kampus' }}
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width:220px">
      <li><h6 class="dropdown-header">Ganti Kampus</h6></li>
      @foreach(App\Models\Kampus::all() as $k)
      <li>
        <form method="POST" action="{{ route('ganti-kampus') }}">
          @csrf
          <input type="hidden" name="kampus_id" value="{{ $k->id }}">
          <button type="submit" class="dropdown-item d-flex align-items-center gap-2 {{ ($kampusAktif?->id == $k->id) ? 'active' : '' }}">
            <i class="bi bi-building-check"></i>
            <span>
              <strong>{{ $k->kode }}</strong><br>
              <small class="text-muted">{{ $k->nama }}</small>
            </span>
          </button>
        </form>
      </li>
      @endforeach
      <li><hr class="dropdown-divider"></li>
      <li>
        <a href="{{ route('kampus.create') }}" class="dropdown-item text-primary">
          <i class="bi bi-plus-circle me-1"></i> Tambah Kampus Baru
        </a>
      </li>
    </ul>
  </div>

  {{-- Mahasiswa Berisiko Alert --}}
  @if(isset($mahasiswaBerisiko) && $mahasiswaBerisiko->count() > 0)
  <div class="dropdown me-2">
    <button class="btn btn-sm btn-light border-0 position-relative" data-bs-toggle="dropdown" aria-expanded="false" style="width: 34px; height: 34px; border-radius: 50%;">
      <i class="bi bi-bell-fill text-danger"></i>
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 9px; margin-left: -5px;">
        {{ $mahasiswaBerisiko->count() }}
      </span>
    </button>
    <div class="dropdown-menu dropdown-menu-end shadow border-danger-subtle p-0" style="width: 340px; max-height: 400px; overflow-y: auto;">
        <div class="bg-danger-subtle text-danger fw-bold p-2 px-3 border-bottom d-flex justify-content-between align-items-center" style="position: sticky; top: 0; z-index: 10;">
            <span><i class="bi bi-exclamation-triangle-fill me-1"></i>Mahasiswa Berisiko</span>
        </div>
        <div class="p-2">
            @foreach ($mahasiswaBerisiko as $risk)
                <div class="d-flex align-items-center justify-content-between p-2 mb-1 border-bottom">
                    <div class="flex-grow-1">
                        <div class="fw-bold text-dark" style="font-size: 13px;">{{ $risk->mahasiswa->nama }}</div>
                        <small class="text-muted" style="font-size: 11px;">
                            {{ $risk->mahasiswa->nim }} · {{ $risk->mahasiswa->kelas->nama }}
                        </small>
                        <div class="small text-secondary" style="font-size: 11px;">
                            <i class="bi bi-book me-1"></i>{{ $risk->mataKuliah->nama }}
                        </div>
                    </div>
                    <div class="text-end ms-2">
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 10px;">
                            {{ $risk->keterangan_gagal ?? 'Gagal' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
  </div>
  @else
  <button class="btn btn-sm btn-light border-0 me-2" style="width: 34px; height: 34px; border-radius: 50%;" title="Semua Mahasiswa Aman" disabled>
      <i class="bi bi-bell text-muted"></i>
  </button>
  @endif

  {{-- User --}}
  <div class="dropdown">
    <button class="d-flex align-items-center gap-2 border-0 bg-transparent dropdown-toggle" data-bs-toggle="dropdown">
      <div style="width:32px;height:32px;border-radius:50%;background:var(--pri);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px">
        {{ strtoupper(substr(auth()->user()->name,0,1)) }}
      </div>
      <span class="d-none d-md-inline text-dark small">{{ auth()->user()->name }}</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
      <li><h6 class="dropdown-header">{{ auth()->user()->email }}</h6></li>
      <li><hr class="dropdown-divider"></li>
      <li>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-left me-1"></i>Keluar</button>
        </form>
      </li>
    </ul>
  </div>
</header>

{{-- MAIN --}}
<main id="main">
  <div class="cw">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2">
      <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show py-2">
      <i class="bi bi-exclamation-circle me-1"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show py-2">
      <strong><i class="bi bi-exclamation-triangle me-1"></i>Kesalahan:</strong>
      <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @yield('content')
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('sbt')?.addEventListener('click',()=>document.getElementById('sb').classList.toggle('open'));
</script>
@stack('scripts')
</body>
</html>
