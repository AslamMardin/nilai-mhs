@extends('layouts.app')
@section('title','Rekap Kampus')
@section('page-title','Laporan · Rekap Kampus')
@section('content')
<div class="row g-3 mb-4">
  @foreach($statistik as $stat)
  <div class="col-md-6">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-building text-primary me-1"></i>{{ $stat['kampus']->nama }}</span>
        <span class="badge bg-primary">{{ $stat['kampus']->kode }}</span>
      </div>
      <div class="card-body">
        <div class="row g-2 text-center mb-3">
          <div class="col-4"><div class="rounded-2 py-2" style="background:#f8fafc"><div class="fw-700 text-primary fs-5">{{ $stat['total_mahasiswa'] }}</div><div class="text-muted" style="font-size:11px">Mahasiswa</div></div></div>
          <div class="col-4"><div class="rounded-2 py-2" style="background:#f0fdf4"><div class="fw-700 text-success fs-5">{{ $stat['total_lulus'] }}</div><div class="text-muted" style="font-size:11px">Lulus</div></div></div>
          <div class="col-4"><div class="rounded-2 py-2" style="background:#fff1f2"><div class="fw-700 text-danger fs-5">{{ $stat['total_gagal'] }}</div><div class="text-muted" style="font-size:11px">Tidak Lulus</div></div></div>
        </div>
        <div class="d-flex justify-content-between small mb-1">
          <span class="text-muted">Tingkat Kelulusan</span>
          <strong class="{{ $stat['pct_lulus']>=75?'text-success':'text-warning' }}">{{ $stat['pct_lulus'] }}%</strong>
        </div>
        <div class="progress mb-3" style="height:10px;border-radius:6px">
          <div class="progress-bar {{ $stat['pct_lulus']>=75?'bg-success':'bg-warning' }}" style="width:{{ $stat['pct_lulus'] }}%"></div>
        </div>
        <div class="d-flex gap-4 small text-muted">
          <span>Rata-rata: <strong>{{ $stat['rata_rata'] }}</strong></span>
          <span>Kelas: <strong>{{ $stat['kampus']->kelas->count() }}</strong></span>
          <span>Matkul: <strong>{{ $stat['kampus']->mataKuliah->count() }}</strong></span>
        </div>
      </div>
    </div>
  </div>
  @endforeach
</div>

@foreach($statistik as $stat)
<div class="card mb-3">
  <div class="card-header"><strong>{{ $stat['kampus']->kode }}</strong> — Daftar Kelas & Mata Kuliah</div>
  <div class="card-body p-0">
    <table class="table table-sm table-hover mb-0">
      <thead class="table-light">
        <tr><th class="ps-3">Kelas</th><th class="text-center">Mahasiswa</th><th class="text-center">Matkul</th><th>Wali Kelas</th><th class="text-center">% Lulus</th></tr>
      </thead>
      <tbody>
        @foreach($stat['kampus']->kelas as $kls)
        @php
          $totalNilai = \App\Models\NilaiAkhir::whereHas('mahasiswa',fn($q)=>$q->where('kelas_id',$kls->id))->whereIn('status_kelulusan',['lulus','tidak_lulus'])->count();
          $lulusKls   = \App\Models\NilaiAkhir::whereHas('mahasiswa',fn($q)=>$q->where('kelas_id',$kls->id))->where('status_kelulusan','lulus')->count();
          $pctKls     = $totalNilai>0?round($lulusKls/$totalNilai*100):0;
        @endphp
        <tr>
          <td class="ps-3"><span class="badge bg-light text-dark border me-1">{{ $kls->kode }}</span>{{ $kls->nama }}</td>
          <td class="text-center">{{ $kls->mahasiswa->count() }}</td>
          <td class="text-center">{{ $kls->mataKuliah->count() }}</td>
          <td class="text-muted small">{{ $kls->wali_kelas??'—' }}</td>
          <td class="text-center"><span class="badge {{ $pctKls>=75?'bg-success':'bg-warning text-dark' }}">{{ $pctKls }}%</span></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endforeach
@endsection
