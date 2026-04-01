@extends('layouts.app')
@section('title','Absensi')
@section('page-title','Akademik · Absensi')
@section('content')
<div class="sh">
  <h5><i class="bi bi-calendar-check text-primary me-2"></i>Pilih Mata Kuliah — Absensi</h5>
</div>
<div class="row g-3">
  @forelse($mataKuliahList as $mk)
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-body">
        <code class="small text-muted">{{ $mk->kode }}</code>
        <h6 class="fw-600 mt-1 mb-1">{{ $mk->nama }}</h6>
        <div class="text-muted small mb-2">{{ $mk->kelas->nama }} · {{ $mk->dosen??'—' }}</div>
        <div class="d-flex gap-2 align-items-center mb-3">
          <span class="badge bg-info text-dark" style="font-size:11px">{{ $mk->label_jenis }}</span>
          <span class="badge bg-light text-dark border" style="font-size:11px">{{ $mk->total_pertemuan }} pertemuan</span>
          <span class="badge bg-secondary" style="font-size:11px">{{ $mk->mahasiswa_count }} mhs</span>
        </div>
        @php
          $pertemuanTercatat = $mk->absensi->unique('pertemuan_ke')->count();
        @endphp
        <div class="progress mb-2" style="height:6px;border-radius:4px">
          <div class="progress-bar bg-success" style="width:{{ $mk->total_pertemuan>0?($pertemuanTercatat/$mk->total_pertemuan*100):0 }}%"></div>
        </div>
        <div class="text-muted small mb-3">{{ $pertemuanTercatat }}/{{ $mk->total_pertemuan }} pertemuan tercatat</div>
        <div class="d-flex gap-2">
          <a href="{{ route('absensi.index',$mk->id) }}" class="btn btn-sm btn-primary flex-grow-1">
            <i class="bi bi-pencil-square me-1"></i>Input
          </a>
          <a href="{{ route('absensi.rekap',$mk->id) }}" class="btn btn-sm btn-outline-secondary flex-grow-1">
            <i class="bi bi-table me-1"></i>Rekap
          </a>
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12"><div class="alert alert-info">Belum ada mata kuliah. <a href="{{ route('matakuliah.create') }}">Tambah mata kuliah</a>.</div></div>
  @endforelse
</div>
@endsection
