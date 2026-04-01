@extends('layouts.app')
@section('title','Transkrip')
@section('page-title','Laporan · Transkrip Nilai')
@section('content')
<div class="card mb-4">
  <div class="card-body py-2">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-md-4">
        <label class="form-label mb-1 small">NIM Mahasiswa</label>
        <input type="text" name="nim" class="form-control" placeholder="Contoh: ITBM2024001" value="{{ $nim??'' }}">
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Cari</button>
      </div>
      @if($mahasiswa)
      <div class="col-md-2">
        <button onclick="window.print()" type="button" class="btn btn-outline-secondary w-100"><i class="bi bi-printer me-1"></i>Cetak</button>
      </div>
      @endif
    </form>
  </div>
</div>

@if($mahasiswa)
<div class="row g-3 mb-3">
  <div class="col-md-5">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div style="width:56px;height:56px;border-radius:50%;background:#1a4a7a;color:#fff;font-size:22px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            {{ strtoupper(substr($mahasiswa->nama,0,1)) }}
          </div>
          <div>
            <div class="fw-700 fs-6">{{ $mahasiswa->nama }}</div>
            <code class="small text-muted">{{ $mahasiswa->nim }}</code>
            <div><span class="badge {{ $mahasiswa->status=='aktif'?'bg-success':'bg-secondary' }} mt-1">{{ ucfirst($mahasiswa->status) }}</span></div>
          </div>
        </div>
        <table class="table table-sm table-borderless mb-0">
          <tr><td class="text-muted small py-1" style="width:80px">Kampus</td><td class="small fw-500">{{ $mahasiswa->kampus->nama }}</td></tr>
          <tr><td class="text-muted small py-1">Kelas</td><td class="small">{{ $mahasiswa->kelas->nama }}</td></tr>
          <tr><td class="text-muted small py-1">JK</td><td class="small">{{ $mahasiswa->jenis_kelamin=='L'?'Laki-laki':'Perempuan' }}</td></tr>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <div class="row g-2 h-100">
      @php
        $rataRata = $transkrip->avg('nilai_akhir')??0;
        $totalSks = $transkrip->sum('sks');
        $lulus    = $transkrip->where('status','lulus')->count();
        $total    = $transkrip->count();
      @endphp
      <div class="col-6"><div class="card text-center py-3"><div class="fw-700 text-primary" style="font-size:28px">{{ number_format($rataRata,2) }}</div><div class="text-muted small">Rata-rata Nilai</div></div></div>
      <div class="col-6"><div class="card text-center py-3"><div class="fw-700 text-success" style="font-size:28px">{{ $totalSks }}</div><div class="text-muted small">Total SKS</div></div></div>
      <div class="col-6"><div class="card text-center py-3"><div class="fw-700 text-info" style="font-size:28px">{{ $total }}</div><div class="text-muted small">Mata Kuliah</div></div></div>
      <div class="col-6"><div class="card text-center py-3"><div class="fw-700 text-success" style="font-size:28px">{{ $lulus }}/{{ $total }}</div><div class="text-muted small">Lulus</div></div></div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header"><i class="bi bi-file-earmark-text text-primary me-1"></i>Transkrip Nilai Lengkap</div>
  <div class="card-body p-0">
    <table class="table table-hover mb-0" style="font-size:13px">
      <thead class="table-light">
        <tr><th class="ps-3">#</th><th>Kode</th><th>Mata Kuliah</th><th class="text-center">SKS</th><th class="text-center">Teori</th><th class="text-center">Prak</th><th class="text-center">NA</th><th class="text-center">Mutu</th><th class="text-center">Kehadiran</th><th class="text-center">Status</th></tr>
      </thead>
      <tbody>
        @forelse($transkrip as $i => $t)
        <tr>
          <td class="ps-3 text-muted">{{ $i+1 }}</td>
          <td><code style="font-size:11px">{{ $t['kode'] }}</code></td>
          <td>{{ $t['nama'] }}</td>
          <td class="text-center">{{ $t['sks'] }}</td>
          <td class="text-center">{{ $t['nilai_teori']>0?$t['nilai_teori']:'—' }}</td>
          <td class="text-center">{{ $t['nilai_prak']>0?$t['nilai_prak']:'—' }}</td>
          <td class="text-center fw-700 fs-6">{{ $t['nilai_akhir'] }}</td>
          <td class="text-center">
            @if($t['huruf_mutu'])<span class="badge badge-{{ strtolower($t['huruf_mutu']) }}">{{ $t['huruf_mutu'] }}</span>@else—@endif
          </td>
          <td class="text-center">
            <span class="badge {{ str_replace('%','',$t['kehadiran'])>=75?'bg-success':'bg-danger' }}">{{ $t['kehadiran'] }}</span>
          </td>
          <td class="text-center">
            @if($t['status']=='lulus')<span class="badge bg-success">Lulus</span>
            @elseif($t['status']=='tidak_lulus')<span class="badge bg-danger">Tidak Lulus</span>
            @else<span class="badge bg-secondary">Belum</span>@endif
          </td>
        </tr>
        @empty
        <tr><td colspan="10" class="text-center text-muted py-4">Belum ada data nilai.</td></tr>
        @endforelse
      </tbody>
      @if($transkrip->count())
      <tfoot class="table-light fw-600">
        <tr>
          <td colspan="3" class="ps-3 text-end">Total / Rata-rata</td>
          <td class="text-center">{{ $totalSks }}</td>
          <td colspan="2"></td>
          <td class="text-center text-primary">{{ number_format($rataRata,2) }}</td>
          <td colspan="3"></td>
        </tr>
      </tfoot>
      @endif
    </table>
  </div>
</div>

@elseif($nim)
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-1"></i>Mahasiswa dengan NIM <strong>{{ $nim }}</strong> tidak ditemukan.</div>
@else
<div class="text-center py-5 text-muted">
  <i class="bi bi-file-earmark-text fs-1 d-block mb-2 opacity-25"></i>
  Masukkan NIM untuk melihat transkrip nilai mahasiswa.
</div>
@endif
@endsection
